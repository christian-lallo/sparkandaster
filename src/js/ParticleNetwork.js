
window.requestAnimFrame = (function() {
   return  window.requestAnimationFrame       || 
         window.webkitRequestAnimationFrame || 
         window.mozRequestAnimationFrame    || 
         window.oRequestAnimationFrame      || 
         window.msRequestAnimationFrame     || 
         function(callback, element){
            window.setTimeout(callback, 1000 / 60);
         };
})();

// Create Particle class
const Particle = function( parent, img ) {

	this.canvas   = parent.canvas;
	this.ctx      = parent.ctx;

   if( img != undefined ) {
      this.img      = img;
      this.rotation = 0;
   }

	this.x = Math.random() * this.canvas.width;
	this.y = Math.random() * this.canvas.height;

	this.velocity = {
   	x: (Math.random() - 0.5) * parent.options.velocity,
   	y: (Math.random() - 0.5) * parent.options.velocity
  }
}

Particle.prototype.update = function () {

  	// Change dir if outside map
	if( this.x > this.canvas.width - 100 || this.x < 100 ) {
  		this.velocity.x = -this.velocity.x
  	}
  	if( this.y > this.canvas.height - 100 || this.y < 100 ) {
    	this.velocity.y = -this.velocity.y
  	}

  	// Update position
  	this.x += this.velocity.x
  	this.y += this.velocity.y
}

Particle.prototype.draw = function () {

   if( this.img != undefined ) {
      this.ctx.save(); 
      // move to the middle of where we want to draw our image
      this.ctx.translate(this.x, this.y);
      // rotate around that point, converting our 
      // angle from degrees to radians 
      this.rotation += 0.2;
      this.ctx.rotate(this.rotation * Math.PI/180);
      // draw it up and to the left by half the width
      // and height of the image 
      this.ctx.drawImage(this.img, -15, -15, 50, 50);
      // and restore the co-ords to how they were when we began
      this.ctx.restore();
   }else{
      this.ctx.beginPath();
      this.ctx.arc(100,75,50,0,2*Math.PI);
      this.ctx.stroke();
   }
}

// Create ParticleNetwork class
const ParticleNetwork = function (canvas, options) {

	this.canvasDiv = canvas;

  	this.canvasDiv.size = {
   	'width': this.canvasDiv.offsetWidth,
   	'height': this.canvasDiv.offsetHeight
  	}

  	// Set options
  	options = options !== undefined ? options : {}
  	this.options = {
   	particleColor: (options.particleColor !== undefined) ? options.particleColor : '#000',
      particleImgs: (options.particleImgs !== undefined) ? options.particleImgs : undefined,
   	background: (options.background !== undefined) ? options.background : '#1a252f',
   	interactive: (options.interactive !== undefined) ? options.interactive : true,
   	velocity: this.setVelocity(options.velocity),
  	}

  	this.init();
}

ParticleNetwork.prototype.init = function () {

	// Create background div
	this.bgDiv = document.createElement('div');
	this.canvasDiv.appendChild(this.bgDiv);

	this.setStyles(this.bgDiv, {
		'position': 'absolute',
		'top': 0,
		'left': 0,
		'bottom': 0,
		'right': 0,
		'z-index': 1
	});

  	// Create canvas & context
  	this.canvas = document.createElement('canvas');
  	this.canvasDiv.appendChild(this.canvas);
  	this.ctx           = this.canvas.getContext('2d', { alpha: false });
   this.globalAlpha = 1;
  	this.canvas.width  = this.canvasDiv.size.width;
  	this.canvas.height = this.canvasDiv.size.height;

  	this.setStyles(this.canvasDiv, { 'position': 'absolute' })
  	this.setStyles(this.canvas, {
  	  'z-index': '20',
  	  'position': 'relative'
  	});

	// Add resize listener to canvas
	window.addEventListener('resize', () => {

		// Check if div has changed size
		if (this.canvasDiv.offsetWidth === this.canvasDiv.size.width && this.canvasDiv.offsetHeight === this.canvasDiv.size.height) {
		  return false
		}

	 	// Scale canvas
	 	this.canvas.width = this.canvasDiv.size.width = this.canvasDiv.offsetWidth;
	 	this.canvas.height = this.canvasDiv.size.height = this.canvasDiv.offsetHeight;

		// Set timeout to wait until end of resize event
		clearTimeout(this.resetTimer)

		this.resetTimer = setTimeout( () => {

         // Reset particles
         this.createParticles();

         // Update canvas
         requestAnimFrame(this.update.bind(this));

		}, 500);
  	});

  	this.createParticles();

  	// Update canvas
  	requestAnimFrame(this.update.bind(this));
}

ParticleNetwork.prototype.createParticles = function () {

   // Initialize particles
   this.particles = [];

   for (var i = 0; i < this.options.particleImgs.length; i++) {

      let img = new Image();
      img.onload = ( event ) => {
         this.particles.push(new Particle(this, img));
      }
      img.src = this.options.particleImgs[i];
   }
}

ParticleNetwork.prototype.update = function () {

	this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

   // Draw particles
   for( var i = 0; i < this.particles.length; i++ ) {
      this.particles[i].update();
      this.particles[i].draw();
   }

   if (this.options.velocity !== 0) {
      requestAnimationFrame(this.update.bind(this))
   }
  
}

// Helper method to set velocity multiplier
ParticleNetwork.prototype.setVelocity = function (velocity) {

   if (velocity === 'fast') {
      return 1;
   } else if (velocity === 'slow') {
      return 0.33;
   } else if (velocity === 'none') {
      return 0;
   }

   return velocity || 2;
}

// Helper method to set multiple styles
ParticleNetwork.prototype.setStyles = function (div, styles) {

   for (var property in styles) {
      div.style[property] = styles[property]
   }
}

export default ParticleNetwork;