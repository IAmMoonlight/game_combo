const colors = [
    { r: 57, g: 139, b: 245 },
    { r: 255, g: 52, b: 125 },
    { r: 255, g: 52, b: 125 },
    { r: 57, g: 115, b: 245 }
];

const PI2 = Math.PI * 2;

class Circle {
    constructor (x, y, radius, rgb) {
        this.x = x;
        this.y = y;
        this.radius = radius;
        this.rgb = rgb;

        this.velX = Math.random() * 5;
        this.velY = Math.random() * 5;

        this.sin = Math.random();
    }

    render (ctx, sceneWidth, sceneHeight) {
        this.sin += 0.01;

        this.radius += Math.sin(this.sin);

        this.x += this.velX;
        this.y += this.velY;

        if (this.x < 0) {
            this.velX *= -1;
            this.x += 10;
        } else if (this.x > sceneWidth) {
            this.velX *= -1;
            this.x -= 10;
        }

        if (this.y < 0) {
            this.velY *= -1;
            this.y += 10;
        } else if (this.y > sceneHeight) {
            this.velY *= -1;
            this.y -= 10;
        }

        ctx.beginPath();
        const g = ctx.createRadialGradient(
            this.x,
            this.y,
            this.radius * 0.01,
            this.x,
            this.y,
            this.radius
        );

        g.addColorStop(0, `rgba(${this.rgb.r}, ${this.rgb.g}, ${this.rgb.b}, 1)`);
        g.addColorStop(1, `rgba(${this.rgb.r}, ${this.rgb.g}, ${this.rgb.b}, 0)`);
        ctx.fillStyle = g;
        // ctx.fillStyle = `rgba(${this.rgb.r}, ${this.rgb.g}, ${this.rgb.b}, 1)`;
        ctx.arc(this.x, this.y, this.radius, 0, PI2, false);
        ctx.fill();
    }
}

class liquidGradient {
    constructor (props = {}) {
        this.canvas = props.domEl;
        this.ctx = this.canvas.getContext('2d');
        this.pixelRatio = 1;

        this.particles = [];
        this.totalParticles = props.circlesAmount || 25;
        this.maxRadius = props.maxCircleRadius || 400;
        this.minRadius = props.minCircleRadius || 700;
        this.width = window.innerWidth;

        window.addEventListener('resize', this.resize.bind(this));
        this.setSize();

        window.requestAnimationFrame(this.render.bind(this));
    }

    setSize () {
        this.stageWidth = this.canvas.offsetWidth;
        this.stageHeight = this.canvas.offsetHeight;

        this.canvas.width = this.stageWidth * this.pixelRatio;
        this.canvas.height = this.stageHeight * this.pixelRatio;
        this.ctx.scale(this.pixelRatio, this.pixelRatio);

        this.createParticles();
    }

    resize () {
        if (window.innerWidth !== this.width) {
            this.width = window.innerWidth;
            this.setSize();
        }
    }

    createParticles () {
        let currColor = 0;
        this.particles = [];

        for (let i = 0; i < this.totalParticles; i++) {
            const item = new Circle(
                Math.random() * this.stageWidth,
                Math.random() * this.stageHeight,
                Math.random() * (this.maxRadius - this.minRadius) + this.minRadius,
                colors[currColor]
            );

            if (++currColor >= colors.length) currColor = 0;

            this.particles[i] = item;
        }
    }

    render () {
        window.requestAnimationFrame(this.render.bind(this));

        this.ctx.clearRect(0, 0, this.stageWidth, this.stageHeight);

        for (let i = 0; i < this.totalParticles; i++) {
            const item = this.particles[i];
            item.render(this.ctx, this.stageWidth, this.stageHeight);
        }
    }
}

if (!!document.querySelector('.floating-gradient')) {
    new liquidGradient({
        domEl: document.querySelector('.floating-gradient'),
        circlesAmount: 20,
        minCircleRadius: 600,
        maxCircleRadius: 700
    });
}


