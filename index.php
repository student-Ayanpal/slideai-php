<?php
$pageTitle = "AI Presentation Generator";
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="gradient-mesh">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>
    <div class="grid-pattern"></div>
    
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i>
                    <span>Powered by Gemini AI</span>
                </div>
                <h1 class="hero-title">
                    Transform ideas into 
                    <span class="typewriter-text" id="typewriter">Stunning Slides</span>
                    <span class="typewriter-cursor"></span>
                </h1>
                <p class="hero-subtitle">
                    Generate professional PowerPoint presentations in seconds. Just enter your topic and let our AI do the heavy lifting.
                </p>
                <div class="hero-buttons">
                    <a href="generator.php" class="btn-primary-hero">
                        <i class="bi bi-magic"></i>
                        Start Generating
                    </a>
                    <a href="#how-it-works" class="btn-secondary-hero">
                        How it Works
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="slide-mockup">
                    <div class="mockup-wrapper">
                        <div class="mockup-glow"></div>
                        <div class="mockup-card">
                            <div class="mockup-header">
                                <div class="mockup-dot dot-red"></div>
                                <div class="mockup-dot dot-yellow"></div>
                                <div class="mockup-dot dot-green"></div>
                            </div>
                            <div class="mockup-content">
                                <div class="mockup-ai-badge">AI Generated</div>
                                <h3 class="mockup-title">The Future of AI</h3>
                                <p class="mockup-subtitle">Exploring Neural Networks</p>
                                <div class="mockup-chart">
                                    <div class="chart-bar bar-1"></div>
                                    <div class="chart-bar bar-2"></div>
                                    <div class="chart-bar bar-3"></div>
                                    <div class="chart-bar bar-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Features</span>
            <h2 class="section-title">Powerful Presentation Tools</h2>
            <p class="section-subtitle">Everything you need to create impactful slides without the effort.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                    <h3 class="feature-title">Instant Generation</h3>
                    <p class="feature-desc">Go from a single sentence to a full deck in under 15 seconds with Gemini AI.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-palette"></i></div>
                    <h3 class="feature-title">Premium Designs</h3>
                    <p class="feature-desc">Every presentation is crafted with modern aesthetics and balanced layouts.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-file-earmark-ppt"></i></div>
                    <h3 class="feature-title">PPTX Export</h3>
                    <p class="feature-desc">Download fully editable PowerPoint files that you can present anywhere.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="how-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Process</span>
            <h2 class="section-title">How SlideAI Works</h2>
        </div>
        <div class="steps-container">
            <div class="step-line"></div>
            <div class="step-card">
                <div class="step-number-wrapper">
                    <div class="step-number-ring"></div>
                    <div class="step-number-inner"><span class="step-number">1</span></div>
                </div>
                <h3 class="step-title">Enter Topic</h3>
                <p class="step-desc">Tell us what you want to talk about in a few words.</p>
            </div>
            <div class="step-card">
                <div class="step-number-wrapper">
                    <div class="step-number-ring"></div>
                    <div class="step-number-inner"><span class="step-number">2</span></div>
                </div>
                <h3 class="step-title">AI Magic</h3>
                <p class="step-desc">Our AI researches and structures your content automatically.</p>
            </div>
            <div class="step-card">
                <div class="step-number-wrapper">
                    <div class="step-number-ring"></div>
                    <div class="step-number-inner"><span class="step-number">3</span></div>
                </div>
                <h3 class="step-title">Download</h3>
                <p class="step-desc">Get your professional .pptx file ready to present.</p>
            </div>
        </div>
    </div>
</section>

<style>
/* Page Specific Styles */
.hero-section { min-height: 100vh; display: flex; align-items: center; position: relative; overflow: hidden; padding-top: 100px; padding-bottom: 60px; }
.gradient-mesh { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden; }
.gradient-orb { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.4; will-change: transform; }
.orb-1 { width: 800px; height: 800px; background: radial-gradient(circle, var(--gradient-purple) 0%, transparent 70%); top: -300px; left: -200px; animation: float-slow 20s ease-in-out infinite; }
.orb-2 { width: 600px; height: 600px; background: radial-gradient(circle, var(--gradient-blue) 0%, transparent 70%); bottom: -200px; right: -150px; animation: float-slow 25s ease-in-out infinite reverse; }
.orb-3 { width: 500px; height: 500px; background: radial-gradient(circle, var(--gradient-cyan) 0%, transparent 70%); top: 40%; left: 50%; opacity: 0.2; animation: float-slow 18s ease-in-out infinite; animation-delay: -5s; }
@keyframes float-slow { 0%, 100% { transform: translate(0, 0) scale(1); } 25% { transform: translate(40px, -30px) scale(1.05); } 50% { transform: translate(-20px, 40px) scale(0.95); } 75% { transform: translate(-40px, -20px) scale(1.02); } }
.grid-pattern { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: linear-gradient(rgba(148, 163, 184, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, 0.03) 1px, transparent 1px); background-size: 60px 60px; mask-image: radial-gradient(ellipse 80% 60% at 50% 30%, black 20%, transparent 70%); -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 30%, black 20%, transparent 70%); }
.hero-content { position: relative; z-index: 1; }
.hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(124, 58, 237, 0.1); border: 1px solid rgba(124, 58, 237, 0.2); padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.875rem; font-weight: 500; color: #a78bfa; margin-bottom: 1.5rem; }
.hero-title { font-size: clamp(2.5rem, 5vw, 4.5rem); font-weight: 900; line-height: 1.05; margin-bottom: 1.5rem; letter-spacing: -0.04em; }
.typewriter-text { background: linear-gradient(135deg, #c4b5fd 0%, var(--gradient-purple) 40%, var(--gradient-blue) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; display: block; min-height: 1.15em; }
.typewriter-cursor { display: inline-block; width: 3px; height: 0.9em; background: linear-gradient(180deg, var(--gradient-purple), var(--gradient-blue)); margin-left: 4px; border-radius: 2px; animation: blink 1s infinite; vertical-align: text-bottom; box-shadow: 0 0 8px var(--gradient-purple); }
@keyframes blink { 0%, 45% { opacity: 1; } 50%, 100% { opacity: 0; } }
.hero-subtitle { font-size: 1.125rem; color: var(--text-secondary); margin-bottom: 2rem; max-width: 480px; line-height: 1.7; }
.hero-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
.btn-primary-hero { background: linear-gradient(135deg, var(--gradient-purple), var(--gradient-blue)); border: none; padding: 0.875rem 2rem; border-radius: 12px; font-weight: 600; font-size: 1rem; color: white; display: inline-flex; align-items: center; gap: 0.625rem; text-decoration: none; transition: all 0.4s ease; box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3); }
.btn-primary-hero:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(124, 58, 237, 0.4); color: white; }
.btn-secondary-hero { background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); padding: 0.875rem 2rem; border-radius: 12px; font-weight: 600; font-size: 1rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 0.625rem; text-decoration: none; transition: all 0.3s ease; backdrop-filter: blur(10px); }
.btn-secondary-hero:hover { background: rgba(255, 255, 255, 0.06); color: var(--text-primary); transform: translateY(-2px); }
.slide-mockup { position: relative; z-index: 1; }
.mockup-wrapper { perspective: 1200px; }
.mockup-glow { position: absolute; top: 50%; left: 50%; width: 120%; height: 120%; transform: translate(-50%, -50%); background: radial-gradient(ellipse at center, var(--glow-purple) 0%, transparent 60%); filter: blur(40px); }
.mockup-card { background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-secondary) 100%); border-radius: 20px; padding: 1.75rem; border: 1px solid var(--border-subtle); box-shadow: 0 32px 64px rgba(0, 0, 0, 0.4); animation: float-card 8s ease-in-out infinite; transform-style: preserve-3d; transform: rotateY(-8deg) rotateX(5deg); }
@keyframes float-card { 0%, 100% { transform: rotateY(-8deg) rotateX(5deg) translateY(0); } 50% { transform: rotateY(-8deg) rotateX(5deg) translateY(-20px); } }
.mockup-header { display: flex; gap: 8px; margin-bottom: 1.25rem; }
.mockup-dot { width: 12px; height: 12px; border-radius: 50%; }
.dot-red { background: #ff6b6b; } .dot-yellow { background: #ffd93d; } .dot-green { background: #6bcb77; }
.mockup-content { background: linear-gradient(135deg, rgba(124, 58, 237, 0.12), rgba(59, 130, 246, 0.08)); border-radius: 14px; padding: 2rem; text-align: center; }
.mockup-ai-badge { display: inline-flex; align-items: center; background: linear-gradient(135deg, var(--gradient-purple), var(--gradient-blue)); padding: 0.375rem 0.875rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem; }
.mockup-title { font-weight: 800; font-size: 1.375rem; margin-bottom: 0.375rem; }
.mockup-chart { display: flex; justify-content: center; align-items: flex-end; gap: 10px; margin-top: 1.5rem; height: 80px; }
.chart-bar { width: 36px; border-radius: 6px 6px 0 0; background: linear-gradient(180deg, var(--gradient-purple), var(--gradient-blue)); }
.bar-1 { height: 35px; } .bar-2 { height: 55px; } .bar-3 { height: 75px; } .bar-4 { height: 50px; }

.features-section { padding: 8rem 0; }
.section-header { text-align: center; margin-bottom: 4rem; }
.section-label { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(124, 58, 237, 0.08); border: 1px solid rgba(124, 58, 237, 0.15); padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.8125rem; font-weight: 600; color: #a78bfa; margin-bottom: 1.25rem; text-transform: uppercase; }
.section-title { font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1rem; }
.section-subtitle { color: var(--text-secondary); font-size: 1.125rem; max-width: 600px; margin: 0 auto; }
.feature-card { background: linear-gradient(135deg, var(--bg-card) 0%, rgba(15, 15, 22, 0.5) 100%); backdrop-filter: blur(20px); border: 1px solid var(--border-subtle); border-radius: 20px; padding: 2rem; text-align: center; transition: all 0.5s ease; height: 100%; position: relative; overflow: hidden; }
.feature-card:hover { transform: translateY(-8px); border-color: var(--border-glow); box-shadow: 0 24px 48px rgba(0, 0, 0, 0.2); }
.feature-icon { width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, var(--gradient-purple), var(--gradient-blue)); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.75rem; }
.feature-title { font-weight: 700; font-size: 1.25rem; margin-bottom: 0.75rem; }
.feature-desc { color: var(--text-secondary); font-size: 0.9375rem; }

.how-section { padding: 8rem 0; background: linear-gradient(180deg, transparent 0%, rgba(124, 58, 237, 0.02) 50%, transparent 100%); }
.steps-container { position: relative; display: flex; justify-content: space-between; max-width: 1000px; margin: 0 auto; }
.step-line { position: absolute; top: 55px; left: 18%; right: 18%; height: 2px; background: repeating-linear-gradient(90deg, transparent, transparent 8px, rgba(124, 58, 237, 0.3) 8px, rgba(59, 130, 246, 0.3) 16px); }
.step-card { position: relative; z-index: 1; text-align: center; flex: 1; max-width: 260px; }
.step-number-wrapper { width: 110px; height: 110px; margin: 0 auto 1.5rem; position: relative; }
.step-number-ring { position: absolute; inset: 0; border-radius: 50%; background: conic-gradient(from 0deg, var(--gradient-purple), var(--gradient-blue), var(--gradient-cyan), var(--gradient-blue), var(--gradient-purple)); padding: 3px; animation: ring-rotate 8s linear infinite; }
@keyframes ring-rotate { to { transform: rotate(360deg); } }
.step-number-inner { position: absolute; inset: 3px; border-radius: 50%; background: var(--bg-primary); display: flex; align-items: center; justify-content: center; z-index: 1; }
.step-number { font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, var(--gradient-purple), var(--gradient-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.step-title { font-weight: 700; font-size: 1.25rem; margin-bottom: 0.625rem; }
.step-desc { color: var(--text-secondary); font-size: 0.9375rem; }


</style>

<script>
// Typewriter Effect
const text = "Stunning Slides";
const speed = 100;
let i = 0;
function typeWriter() {
    if (i < text.length) {
        document.getElementById("typewriter").innerHTML += text.charAt(i);
        i++;
        setTimeout(typeWriter, speed);
    }
}
// Clear and start
document.getElementById("typewriter").innerHTML = "";
typeWriter();
</script>

<?php include 'includes/footer.php'; ?>
