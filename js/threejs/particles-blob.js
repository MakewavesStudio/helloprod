class Sphere extends THREE.Object3D {
	constructor() {
		super();
		this.uniforms = {
			time: { value: 0.0 },
			uFrequency: {
				value:0.5
			},
			uAmplitude: {
				value:0.5
			}
		};
		this.render();
	}

	render() {
		let segments = 128
		this.geometry = new THREE.SphereBufferGeometry(10, segments, segments);
		this.material = new THREE.ShaderMaterial({
			uniforms: this.uniforms,
			vertexShader: document.getElementById("vertexShader").textContent,
			fragmentShader: document.getElementById("fragmentShader").textContent
		});

		this.particles = new THREE.Points(this.geometry, this.material);
		let array = getSphere((segments+1)*(segments+1),1)
		this.geometry.addAttribute('aPosition',new THREE.BufferAttribute(array,3));
		this.add(this.particles);
	}

	update(t) {
		this.uniforms.time.value = t;
	}
}

//main app
class App {
	constructor() {
		this.time = 0;
		this.speedTime = 0.5;
		this.clock = new THREE.Clock();
		this.init();
		window.addEventListener("resize", this.onWindowResize.bind(this), false);
		window.addEventListener("orientationchange", this.onWindowResize.bind(this), false);
	}

	init() {
		// renderer
		this.renderer = new THREE.WebGLRenderer({ canvas: document.getElementsByTagName("CANVAS")[0], antialias: true });
		this.renderer.setSize(window.innerWidth, window.innerHeight);
		this.renderer.setPixelRatio = window.devicePixelRatio;
		document.body.appendChild(this.renderer.domElement);

		// scene
		this.scene = new THREE.Scene();

		// camera
		this.camera = new THREE.PerspectiveCamera(
      // 45,
			62,
			window.innerWidth / window.innerHeight,
			1,
			10000
		);
		this.camera.position.set(2.5, 2.5, 2.5);

		// controls
		this.controls = new THREE.OrbitControls(
			this.camera,
			document.querySelector("canvas")
		);
		// this.controls.enabled = true;
		this.controls.enabled = false;
		this.controls.enablePan = false;

		// ambient light
		this.scene.add(new THREE.AmbientLight(0x222222));

		// directional light
		// this.light = new THREE.DirectionalLight(0xffffff, 1);
		// this.light.position.set(20, 20, 0);
		// this.scene.add(this.light);

		// axes
		// this.scene.add(new THREE.AxesHelper(20));

		let geometry = new THREE.BoxGeometry(10, 10, 10);
		let material = new THREE.ShaderMaterial({
			uniforms: {},
			vertexShader: document.querySelector("#vertexShader").textContent,
			fragmentShader: document.querySelector("#fragmentShader").textContent
		});
		let cube = new THREE.Mesh(geometry, material);
		// this.scene.add(cube);

		// loadModel({url:'https://rocheclement.fr/public/models/WaltHead.obj'})
		// .then((model)=>{
		// 	this.model = model.media
		// 	this.scene.add(this.model)
		// })

		this.createSphere()

		this.load().then(assets => {
			this.addPostProcessing(assets);
			// this.isPostProcessingEnabled = true;
			this.isPostProcessingEnabled = false;

			// this.addGUI();
		});

		//animation loop
		this.renderer.setAnimationLoop(this.render.bind(this));
	}

	createSphere() {
		this.sphere = new Sphere();
		this.scene.add(this.sphere);
	}

	render() {
		// this.clock.update();
		this.time = this.time + this.clock.getDelta() * this.speedTime;

		this.sphere.update(this.time);

		Boolean(this.isPostProcessingEnabled)
			? this.composer.render(this.clock.getDelta())
			: this.renderer.render(this.scene, this.camera);
	}

	load() {
		const assets = new Map();
		const loadingManager = new THREE.LoadingManager();

		return new Promise((resolve, reject) => {
			loadingManager.onError = reject;
			loadingManager.onProgress = (item, loaded, total) => {
				if (loaded === total) {
					resolve(assets);
				}
			};

			const searchImage = new Image();
			const areaImage = new Image();

			searchImage.addEventListener("load", function() {
				assets.set("smaa-search", this);
				loadingManager.itemEnd("smaa-search");
			});

			areaImage.addEventListener("load", function() {
				assets.set("smaa-area", this);
				loadingManager.itemEnd("smaa-area");
			});

			// Register the new image assets.
			loadingManager.itemStart("smaa-search");
			loadingManager.itemStart("smaa-area");

			// Load the images asynchronously.
			searchImage.src = PP.SMAAEffect.searchImageDataURL;
			areaImage.src = PP.SMAAEffect.areaImageDataURL;
		});
	}

	addPostProcessing(assets) {
		// this.renderer = renderer;
		this.composer = new PP.EffectComposer(this.renderer);

		this.noiseEffect = new PP.NoiseEffect({ premultiply: true });
		this.vignetteEffect = new PP.VignetteEffect();
		this.bloomEffect = new PP.BloomEffect();

		this.SMAAEffect = new PP.SMAAEffect(
			assets.get("smaa-search"),
			assets.get("smaa-area")
		);
		this.SMAAEffect.setOrthogonalSearchSteps(112);
		this.SMAAEffect.setEdgeDetectionThreshold(0.5);
		this.chromaticAberrationEffect = new PP.ChromaticAberrationEffect();
		this.chromaticAberrationEffect.offset.x = 0.001
		this.chromaticAberrationEffect.offset.y = 0.001

		this.renderPass = new PP.RenderPass(this.scene, this.camera);
		this.effectPass = new PP.EffectPass(this.camera, this.SMAAEffect);

		this.effectPass2 = new PP.EffectPass(
			this.chromaticAberrationEffect,
			this.bloomEffect,
			this.chromaticAberrationEffect
		);

		// this.noiseEffect.blendMode.opacity.value = 0.75;
		this.effectPass2.renderToScreen = true;

		this.composer.addPass(this.renderPass);
		this.composer.addPass(this.effectPass);
		this.composer.addPass(this.effectPass2);
	}

	addGUI() {
		this.gui = new dat.GUI();

		this.params = {
			postprocessing: {
				// enabled: true,
				enabled: false,
				bloom: {
					blendFunction: PP.BlendFunction.SCREEN,
					resolutionScale: 0.5,
					kernelSize: PP.KernelSize.LARGE,
					distinction: 1.0,
					dithering: false
				},
				chroma: {
					offset: {
						x: 0,
						y: 0
					}
				},
				SMAA: {
					searchStep: 112,
					edgeDetectionThreshold: 0.5
				}
			}
		};

		let pp = this.gui.addFolder("post-processing");
		// pp.open();
		pp.add(this, "isPostProcessingEnabled").name("enabled");

		//bloom
		let bloom = pp.addFolder("bloom");
		bloom.open();

		bloom
			.add(this.params.postprocessing.bloom, "resolutionScale", 0.01, 1)
			.name("resolution")
			.onChange(value => {
				this.bloomEffect.setResolutionScale(value);
			});

		bloom
			.add(this.params.postprocessing.bloom, "kernelSize", PP.KernelSize)
			.name("kernel size")
			.onChange(value => {
				this.bloomEffect.kernelSize = value;
			});

		let luminance = bloom.addFolder("Luminance");
		luminance.open();
		luminance
			.add(this.params.postprocessing.bloom, "distinction", 1, 10)
			.name("distinction")
			.onChange(value => {
				this.bloomEffect.distinction = value;
			});

		// bloom
		// 	.add(this.params.postprocessing.bloom, "blendFunction", PP.BlendFunction)
		// 	.name("blend mode")
		// 	.onChange(value => {
		// 		this.bloomEffect.blendMode.blendFunction = parseInt(value);
		// 	});

		bloom
			.add(this.params.postprocessing.bloom, "dithering")
			.name("dithering")
			.onChange(value => {
				this.bloomEffect.dithering = value;
			});

		let chroma = pp.addFolder("chromatic aberration");
		chroma.open();

		let offset = chroma.addFolder("offset");
		offset
			.add(this.params.postprocessing.chroma.offset, "x", -0.01, 0.01)
			.step(0.001)
			.onChange(value => {
				this.chromaticAberrationEffect.offset.x = value;
			});

		offset
			.add(this.params.postprocessing.chroma.offset, "y", -0.01, 0.01)
			.step(0.001)
			.onChange(value => {
				this.chromaticAberrationEffect.offset.y = value;
			});

		let SMAA = pp.addFolder("SMAA");
		SMAA.open();

		SMAA.add(this.params.postprocessing.SMAA, "searchStep", 0, 112)
			.name("search step")
			.onChange(value => {
				this.SMAAEffect.setOrthogonalSearchSteps(value);
			});

		SMAA.add(this.params.postprocessing.SMAA, "edgeDetectionThreshold", 0.05, 0.5)
			.name("sensitivity")
			.step(0.01)
			.onChange(value => {
				this.SMAAEffect.setEdgeDetectionThreshold(value);
			});

		this.gui.add(this.sphere.uniforms.uFrequency,'value',0,2,0.001).name('frequency')
		this.gui.add(this.sphere.uniforms.uAmplitude,'value',0,1,0.001).name('amplitude')
	}

	onWindowResize() {
		var _this = this;
		setTimeout(function(){
			_this.camera.aspect = window.innerWidth / window.innerHeight;
			_this.camera.updateProjectionMatrix();
			$('.home canvas').css({transition: '0.5s all cubic-bezier(0.165, 0.84, 0.44, 1)'});
			_this.renderer.setSize(window.innerWidth, window.innerHeight);

		}, 250);
	}
}

const PP = POSTPROCESSING;

const simplex = new SimplexNoise();

Number.prototype.map = function(in_min, in_max, out_min, out_max) {
	return (this - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
};

function loadModel(model) {
	return new Promise((resolve, reject) => {
		const ext = model.url.split(".").pop();

		switch (ext) {
			case "obj": {
				const loader = new THREE.OBJLoader();

				// load a resource
				loader.load(
					// resource URL
					model.url,
					// Function when resource is loaded
					object => {
						resolve({ id: model.id, media: object, type: "obj" });
					},

					() => {},
					() => {
						reject("An error happened with the model import.");
					}
				);
				break;
			}

			case "gltf": {
				const loader = new THREE.GLTFLoader();

				// load a resource
				loader.load(
					// resource URL
					model.url,
					// Function when resource is loaded
					object => {
						resolve({ id: model.id, media: object, type: "gltf" });
					},

					() => {},
					() => {
						reject("An error happened with the model import.");
					}
				);
				break;
			}

			default: {
				const loader = new THREE.OBJLoader();

				// load a resource
				loader.load(
					// resource URL
					model.url,
					// Function when resource is loaded
					object => {
						resolve({ id: model.id, media: object, type: "obj" });
					},

					() => {},
					() => {
						reject("An error happened with the model import.");
					}
				);
			}
		}
	});
}

        function getPoint(v,size)
        {
            v.x = Math.random() * 2 - 1 ;
            v.y = Math.random() * 2 - 1 ;
            v.z = Math.random() * 2 - 1 ;
            if(v.length()>1)return getPoint(v,size);
            return v.normalize().multiplyScalar(size);
        }

        function getSphere( count, size ){

            var len = count * 3;
            var data = new Float32Array( len );
            var p = new THREE.Vector3();
            for( var i = 0; i < len; i+=3 )
            {
                getPoint( p, size );
                data[ i     ] = p.x;
                data[ i + 1 ] = p.y;
                data[ i + 2 ] = p.z;
            }
            return data;
        }

//init app
const app = new App();
