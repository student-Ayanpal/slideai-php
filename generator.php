<?php
$pageTitle = "Generate Presentation";
include 'includes/header.php';
?>

<!-- Progress Bar -->
<div class="progress-bar-top" id="progressBar"></div>

<div class="generator-container">
    <!-- Left Panel -->
    <div class="left-panel">
        <h1 class="page-title">
            <span class="sparkle">&#10024;</span>
            Generate Presentation
        </h1>

        <form id="generateForm">
            <!-- Topic Input -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-lightbulb"></i>
                    Presentation Topic
                </label>
                <input 
                    type="text" 
                    class="form-input" 
                    id="topicInput" 
                    placeholder="e.g., Introduction to Machine Learning"
                    maxlength="100"
                    autocomplete="off"
                    required
                >
            </div>

            <!-- Details Textarea -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-text-paragraph"></i>
                    Additional Details
                </label>
                <textarea 
                    class="form-textarea" 
                    id="detailsInput" 
                    placeholder="Add any specific points, audience info, or requirements..."
                    maxlength="500"
                ></textarea>
                <div class="input-footer">
                    <span class="char-counter" id="charCounter">0 / 500</span>
                </div>
            </div>

            <!-- Slide Count -->
            <div class="form-group">
                <div class="range-header">
                    <label class="form-label">
                        <i class="bi bi-layers"></i>
                        Number of Slides
                    </label>
                    <span class="slide-badge" id="slideBadge">6</span>
                </div>
                <input type="range" class="form-range" id="slideCount" min="3" max="12" value="6">
                <div class="range-labels">
                    <span>3 Slides</span>
                    <span>12 Slides</span>
                </div>
            </div>

            <!-- Tone Selection -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-chat-quote"></i>
                    Presentation Tone
                </label>
                <div class="tone-pills">
                    <div class="tone-pill selected" data-tone="professional">Professional</div>
                    <div class="tone-pill" data-tone="creative">Creative</div>
                    <div class="tone-pill" data-tone="educational">Educational</div>
                    <div class="tone-pill" data-tone="minimalist">Minimalist</div>
                </div>
            </div>

            <button type="submit" class="generate-btn" id="submitBtn">
                <span class="btn-text">Generate with AI</span>
                <i class="bi bi-stars btn-icon"></i>
                <div class="spinner"></div>
            </button>
            
            <p class="keyboard-hint">Press <kbd>Enter</kbd> to generate</p>
        </form>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="empty-state" id="emptyState">
            <div class="empty-state-icon">
                <!-- SVG Icon -->
                <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="80" stroke="url(#paint0_linear)" stroke-width="2" stroke-dasharray="8 8"/>
                    <path d="M70 100L90 120L130 80" stroke="url(#paint1_linear)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" opacity="0.2"/>
                    <defs>
                        <linearGradient id="paint0_linear" x1="20" y1="20" x2="180" y2="180" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#7C3AED"/>
                            <stop offset="1" stop-color="#3B82F6"/>
                        </linearGradient>
                        <linearGradient id="paint1_linear" x1="70" y1="80" x2="130" y2="120" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#7C3AED"/>
                            <stop offset="1" stop-color="#3B82F6"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h3>Ready to create?</h3>
            <p>Fill in the details on the left to generate your presentation preview.</p>
        </div>

        <div class="slides-container" id="slidesContainer">
            <!-- Generated slides will appear here -->
        </div>
    </div>
</div>

<?php include 'includes/loading-overlay.php'; ?>

<style>
/* Generator Specific Styles */
.generator-container { display: flex; min-height: 100vh; padding: 1.5rem; gap: 1.5rem; }
.left-panel { width: 40%; background: linear-gradient(135deg, var(--bg-card) 0%, rgba(15, 15, 22, 0.8) 100%); backdrop-filter: blur(20px); border-radius: 24px; padding: 2rem; border: 1px solid var(--border-subtle); height: fit-content; position: sticky; top: 1.5rem; }
.page-title { font-size: 1.75rem; font-weight: 900; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; }
.form-group { margin-bottom: 1.75rem; }
.form-label { font-weight: 600; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.625rem; display: flex; align-items: center; gap: 0.375rem; text-transform: uppercase; }
.form-input, .form-textarea { width: 100%; background: var(--bg-input); border: 1px solid var(--border-subtle); border-radius: 14px; padding: 1rem 1.25rem; color: var(--text-primary); font-size: 0.9375rem; }
.form-textarea { resize: none; min-height: 110px; }
.slide-badge { background: var(--gradient); padding: 0.375rem 0.875rem; border-radius: 20px; font-weight: 700; }
.tone-pills { display: flex; gap: 0.625rem; flex-wrap: wrap; }
.tone-pill { padding: 0.625rem 1.125rem; border-radius: 10px; background: var(--bg-input); border: 1px solid var(--border-subtle); color: var(--text-secondary); cursor: pointer; }
.tone-pill.selected { background: var(--gradient); color: white; border-color: transparent; }
.generate-btn { width: 100%; padding: 1.125rem; border-radius: 14px; background: var(--gradient); color: white; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.75rem; border: none; margin-top: 2rem; cursor: pointer; }
.right-panel { width: 60%; min-height: calc(100vh - 3rem); }
.empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; text-align: center; }
.empty-state-icon { width: 180px; height: 180px; margin-bottom: 2rem; }
.range-labels { display: flex; justify-content: space-between; font-size: 0.8125rem; color: var(--text-muted); margin-top: 0.5rem; }
</style>

<?php 
$extraScripts = '
<script src="assets/js/generator.js"></script>
';
include 'includes/footer.php'; 
?>
