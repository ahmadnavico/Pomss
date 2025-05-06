<div wire:ignore>
    <!-- Include stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <!-- Create the editor container -->
    <div id="{{ $quillId }}" style="height: 500px;">{!! $value ? $value : '' !!}</div>
    
    <div style="border: 1px solid #cccccc;">
        <p class="dark-text p-2"><strong>Max Words </strong> (<span
            id="counter{{ $quillId }}">0</span>/{{ getSettingValue('post-content-limit') ?? 150 }})</p>
        <p class="dark-text p-2"><strong>Max Links </strong>(<span
            id="linkCounter{{ $quillId }}">0</span>/{{ getSettingValue('post-links-limit') ?? 3 }})</p>
    </div>
    <!-- Hidden textarea to store content -->

    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <!-- Initialize Quill editor -->
    <script>
        class WordCounter {
            constructor(quill, options) {
                this.quill = quill;
                this.options = options;
                this.container = document.querySelector(options.container);
                quill.on(Quill.events.TEXT_CHANGE, this.update.bind(this));
            }

            calculate() {
                const text = this.quill.getText();
                const trimmed = text.trim();
                return trimmed.length > 0 ? trimmed.split(/\s+/).length : 0;
            }

            update() {
                const length = this.calculate();
                this.container.innerText = `${length} words`;
                const contentLength = {{ getSettingValue('post-content-limit') ?? 1500 }};
                if (length > contentLength) {
                    this.container.style.color = 'red';
                } else {
                    this.container.style.color = '';
                }
            }
        }

        class LinkCounter {
            constructor(quill, options) {
                this.quill = quill;
                this.options = options;
                this.container = document.querySelector(options.container);
                quill.on(Quill.events.TEXT_CHANGE, this.update.bind(this));
            }

            calculate() {
                const html = this.quill.root.innerHTML;
                // Fixed regex to more accurately count links
                // This counts actual <a> tags without counting instances where 'a' might be in other tags
                const linkCount = (html.match(/<a[\s>]/g) || []).length;
                console.log('Link count:', linkCount, 'HTML:', html);
                return linkCount;
            }

            update() {
                const length = this.calculate();
                this.container.innerText = length;

                const linkLimit = {{ getSettingValue('post-links-limit') ?? 3 }};
                if (length > linkLimit) {
                    this.container.style.color = 'red';
                } else {
                    this.container.style.color = '';
                }
            }
        }

        Quill.register('modules/wordCounter', WordCounter);
        Quill.register('modules/linkCounter', LinkCounter);

        const options = {
            modules: {
                wordCounter: {
                    container: '#counter{{ $quillId }}'
                },
                linkCounter: {
                    container: '#linkCounter{{ $quillId }}'
                },
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    ['link'],
                    ['image'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }, {
                        'list': 'check'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'align': []
                    }],
                ],
            },
            placeholder: 'Write your blog post here...',
            theme: 'snow',
        };

        const quill = new Quill('#{{ $quillId }}', options);
    </script>
</div>
