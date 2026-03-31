@push('ptr-styles')
<style>
.ptr-indicator {
    position: fixed;
    top: 56px;
    left: 0;
    right: 0;
    height: 50px;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    z-index: 999;
    transform: translateY(-100%);
    transition: transform 0.3s ease;
}

.ptr-indicator.active {
    transform: translateY(0);
}

.ptr-indicator.refreshing {
    background: var(--success);
}

.ptr-indicator i {
    font-size: 20px;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush

<div id="pull-to-refresh" class="ptr-indicator">
    <i class='bx bx-down-arrow'></i>
    <span>Tirez pour actualiser</span>
</div>

<script>
(function() {
    let startY = 0;
    let isRefreshing = false;
    let isPulling = false;
    const pullIndicator = document.getElementById('pull-to-refresh');
    const pullThreshold = 70;

    document.addEventListener('touchstart', function(e) {
        if (window.scrollY === 0 && !isRefreshing) {
            startY = e.touches[0].clientY;
            isPulling = true;
        }
    }, { passive: true });

    document.addEventListener('touchmove', function(e) {
        if (window.scrollY === 0 && isPulling && !isRefreshing) {
            const currentY = e.touches[0].clientY;
            const diff = currentY - startY;
            
            if (diff > 20) {
                pullIndicator.classList.add('active');
                if (diff > pullThreshold) {
                    pullIndicator.querySelector('i').className = 'bx bx-refresh';
                    pullIndicator.querySelector('span').textContent = 'Relâchez pour actualiser';
                } else {
                    pullIndicator.querySelector('i').className = 'bx bx-down-arrow';
                    pullIndicator.querySelector('span').textContent = 'Tirez pour actualiser';
                }
            } else {
                pullIndicator.classList.remove('active');
            }
        }
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        if (isPulling && !isRefreshing && pullIndicator.classList.contains('active')) {
            const diff = e.changedTouches[0].clientY - startY;
            if (diff > pullThreshold) {
                isRefreshing = true;
                pullIndicator.classList.add('refreshing');
                pullIndicator.querySelector('i').className = 'bx bx-sync';
                pullIndicator.querySelector('span').textContent = 'Actualisation...';
                window.location.reload();
            }
        }
        
        isPulling = false;
        pullIndicator.classList.remove('active');
        setTimeout(() => { isRefreshing = false; }, 1000);
    }, { passive: true });

    document.addEventListener('touchcancel', function() {
        isPulling = false;
        pullIndicator.classList.remove('active');
    }, { passive: true });
})();
</script>
