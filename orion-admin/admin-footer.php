        </main>
        <footer class="bg-slate-50 border-t border-slate-200 py-4 text-center text-sm text-slate-500 shrink-0">
            <p>&copy; <?php echo date('Y'); ?> Orion CMS. Dibangun oleh <a href="https://github.com/diskonnekted" target="_blank" class="text-orion-600 hover:underline">Diskonnekted</a> untuk dipakai secara gratis.</p>
        </footer>
    </div>
</div>

<div id="orion-confirm-overlay" class="fixed inset-0 bg-slate-900/50 flex items-center justify-center z-40 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6 border border-slate-100">
        <h2 class="text-base font-semibold text-slate-900 mb-2">Konfirmasi</h2>
        <p id="orion-confirm-message" class="text-sm text-slate-600 mb-6 leading-relaxed"></p>
        <div class="flex justify-end gap-3">
            <button id="orion-confirm-cancel" type="button" class="px-4 py-2 text-sm rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                Batal
            </button>
            <button id="orion-confirm-ok" type="button" class="px-4 py-2 text-sm rounded-xl bg-orion-600 text-white hover:bg-orion-700 shadow-sm shadow-orion-600/30 transition-colors">
                Ya, lanjut
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('orion-confirm-overlay');
    var messageEl = document.getElementById('orion-confirm-message');
    var btnOk = document.getElementById('orion-confirm-ok');
    var btnCancel = document.getElementById('orion-confirm-cancel');
    var currentResolve = null;

    function openConfirm(message) {
        if (!overlay || !messageEl) {
            return Promise.resolve(true);
        }
        messageEl.textContent = message || '';
        overlay.classList.remove('hidden');
        return new Promise(function(resolve) {
            currentResolve = resolve;
        });
    }

    function closeConfirm() {
        overlay.classList.add('hidden');
        currentResolve = null;
    }

    if (btnOk) {
        btnOk.addEventListener('click', function() {
            if (currentResolve) {
                currentResolve(true);
            }
            closeConfirm();
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', function() {
            if (currentResolve) {
                currentResolve(false);
            }
            closeConfirm();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                if (currentResolve) {
                    currentResolve(false);
                }
                closeConfirm();
            }
        });
    }

    function attachConfirmHandlers() {
        var elements = document.querySelectorAll('[data-orion-confirm]');
        elements.forEach(function(el) {
            if (el.dataset.orionConfirmBound === '1') {
                return;
            }
            var message = el.getAttribute('data-orion-confirm') || '';

            if (el.tagName === 'FORM') {
                el.addEventListener('submit', function(e) {
                    if (el.dataset.orionConfirmed === '1') {
                        el.dataset.orionConfirmed = '';
                        return;
                    }
                    e.preventDefault();
                    openConfirm(message).then(function(result) {
                        if (result) {
                            el.dataset.orionConfirmed = '1';
                            el.submit();
                        }
                    });
                });
            } else {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    var href = el.getAttribute('href');
                    var target = el.getAttribute('target');
                    var action = el.getAttribute('data-orion-confirm-action');
                    var targetSelector = el.getAttribute('data-orion-target');

                    openConfirm(message).then(function(result) {
                        if (!result) {
                            return;
                        }

                        if (action === 'remove' && targetSelector) {
                            var targetEl = document.querySelector(targetSelector);
                            if (targetEl && targetEl.parentNode) {
                                targetEl.parentNode.removeChild(targetEl);
                            }
                            return;
                        }

                        if (href) {
                            if (target === '_blank') {
                                window.open(href, '_blank');
                            } else {
                                window.location.href = href;
                            }
                        } else if (el.tagName === 'BUTTON' && el.type === 'submit' && el.form) {
                            el.form.submit();
                        }
                    });
                });
            }

            el.dataset.orionConfirmBound = '1';
        });
    }

    attachConfirmHandlers();
    window.orionAttachConfirmHandlers = attachConfirmHandlers;
});
</script>

</body>
</html>
