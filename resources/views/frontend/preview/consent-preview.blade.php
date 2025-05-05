@extends('frontend.company.layout')

@section('content')
    <div class="card" style="margin-bottom: 30px; width: 100%;">
        <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="page-title">Consent Preveiw <i class=""
                    style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
        </div>
        <div class="card-body" style="padding-top: 0;">
            <br> <br> 
            <!-- Tabs Navigation -->
            <div style="display: none; margin-bottom: 20px;">
                <a href="#" class="tab-link active"
                    style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; background-color: white; color: #333; font-weight: 500;   margin-right: 5px;">
                    Preview
                </a>
                {{-- <a href="{{ route('frontend.implementation.embeddings', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'embeddings' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'embeddings' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Embeddings
            </a>
            <a href="{{ route('frontend.implementation.data-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'data-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'data-layer' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Data Layer & Events
            </a>
            <a href="{{ route('frontend.implementation.ab-testing', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'ab-testing' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'ab-testing' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }}">
                A/B Testing
            </a> --}}
            </div>
            <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

            <!-- Script Tag Content -->
            <div class="tab-content">
                <!-- CMP Implementation Section -->
                <div class="implementation-section">
<br>                  

                    <!-- Live Version Section -->
                    <div class="implementation-panel">
                        <div class="panel-header">
                            <h5>Live Version</h5>
                            <div class="version-badge">
                                {{-- <i class="fas fa-bolt"></i> Displaying Live Version --}}
                            </div>
                            <div class="version-number"></div>
                        </div>
                        <p class="panel-description">
                            This is how your consent banner will look to users. You can interact with the preview to see how
                            it behaves in real-time.
                        </p>

                        <div>
                            <iframe 
                            src="{{ route('frontend.preview.iframe-preview', ['config_id' => $configuration->id]) }}"

                            width="100%" height="800"
                                style="border: none;"
                                 id="preview-iframe" name="preview-iframe" scrolling="yes"
                                allowtransparency="true" allowfullscreen="true" frameborder="0">
                            </iframe>




                        </div>


                        <!-- Blocking Options -->
                        {{-- <div class="blocking-options">
                            <button class="btn-blocking active">Manual Blocking</button>
                            <button class="btn-blocking">Auto Blocking</button>
                        </div> --}}

                        <!-- Script Code Block -->

                    </div>





                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        /* Implementation Sections */
        .implementation-section {
            margin-bottom: 30px;
        }

        .implementation-section h4 {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 500;
        }

        .section-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .gtm-guide-link {
            color: #0066cc;
            text-decoration: none;
            font-weight: 500;
        }

        .gtm-guide-link:hover {
            text-decoration: underline;
        }

        /* Implementation Panels */
        .implementation-panel {
            border: 1px solid #e6e8eb;
            border-radius: 8px;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .panel-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e6e8eb;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
        }

        .panel-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
            flex: 1;
        }

        .version-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #f0f7ff;
            color: #0066cc;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            margin-right: 10px;
        }

        .version-number {
            font-size: 14px;
            color: #666;
        }

        .panel-description {
            padding: 15px 20px;
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        .panel-description code {
            background-color: #f8f9fa;
            padding: 2px 5px;
            border-radius: 4px;
            font-family: monospace;
            color: #d63384;
        }

        /* Premium Banner */
        .premium-banner {
            background-color: #fff8e1;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0 20px 20px;
            border-radius: 4px;
        }

        .premium-banner-icon {
            background-color: #ffd600;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .premium-banner-text {
            flex: 1;
        }

        .premium-banner-text h4 {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: 500;
        }

        .premium-banner-text p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .btn-upgrade {
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-upgrade:hover {
            background-color: #0052a3;
        }

        /* Blocking Options */
        .blocking-options {
            display: flex;
            gap: 10px;
            padding: 0 20px 20px;
        }

        .btn-blocking {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-blocking.active {
            background-color: #0066cc;
            color: white;
            border-color: #0066cc;
        }

        /* Code Block */
        .code-block {
            background-color: #f8f9fa;
            border: 1px solid #e6e8eb;
            border-radius: 4px;
            padding: 15px;
            margin: 0 20px 20px;
            position: relative;
        }

        .code-block pre {
            margin: 0;
            overflow-x: auto;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 14px;
            color: #333;
        }

        .btn-copy-code {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 16px;
            padding: 5px;
        }

        .btn-copy-code:hover {
            color: #0066cc;
        }

        /* Collapsible Sections */
        .collapsible-section {
            border: 1px solid #e6e8eb;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .collapsible-header {
            padding: 15px 20px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .collapsible-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        .btn-toggle {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 16px;
        }

        .collapsible-content {
            padding: 15px 20px;
            border-top: 1px solid #e6e8eb;
        }

        /* Preview Section */
        .preview-section {
            padding: 0 20px 20px;
            text-align: center;
        }

        .btn-preview {
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-preview:hover {
            background-color: #0052a3;
        }

        /* Preview Modal */
        .preview-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .preview-modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 50px auto;
            padding: 0;
            border-radius: 8px;
            width: 80%;
            max-width: 1000px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .preview-modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e6e8eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-modal-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }

        .preview-close {
            color: #aaa;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .preview-close:hover {
            color: #333;
        }

        .preview-iframe-container {
            height: 600px;
            overflow: hidden;
        }

        #preview-iframe {
            width: 100%;
           
            border: none;
        }
    </style>

    <script>
        // Copy code functionality
        document.querySelectorAll('.btn-copy-code').forEach(function(button) {
            button.addEventListener('click', function() {
                const codeBlock = this.previousElementSibling;
                const codeText = codeBlock.textContent;

                // Create a temporary textarea element to copy text
                const textarea = document.createElement('textarea');
                textarea.value = codeText;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                // Visual feedback
                const originalIcon = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalIcon;
                }, 1500);
            });
        });

        // Blocking option toggle
        document.querySelectorAll('.btn-blocking').forEach(function(button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.btn-blocking').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        // Collapsible sections toggle
        document.querySelectorAll('.collapsible-header').forEach(function(header) {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const toggleBtn = this.querySelector('.btn-toggle i');

                if (content.style.display === 'none' || content.style.display === '') {
                    content.style.display = 'block';
                    toggleBtn.classList.remove('fa-chevron-down');
                    toggleBtn.classList.add('fa-chevron-up');
                } else {
                    content.style.display = 'none';
                    toggleBtn.classList.remove('fa-chevron-up');
                    toggleBtn.classList.add('fa-chevron-down');
                }
            });
        });

        // Preview functionality
        const previewBtn = document.querySelector('.btn-preview');
        const previewModal = document.getElementById('preview-modal');
        const previewClose = document.querySelector('.preview-close');

        previewBtn.addEventListener('click', function() {
            previewModal.style.display = 'block';
        });

        previewClose.addEventListener('click', function() {
            previewModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === previewModal) {
                previewModal.style.display = 'none';
            }
        });
    </script>
@endsection
