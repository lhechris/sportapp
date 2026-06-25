@props(['nextElementId'])

<div 
    x-data 
    x-init="
        const el = document.getElementById('{{ $nextElementId }}');
        
        if (el) {
            const container = $refs.container;

            const elTop = el.getBoundingClientRect().top;
            const containerTop = container.getBoundingClientRect().top;

            const offset = elTop - containerTop + container.scrollTop;

            container.scrollTo({
                top: offset,
                behavior: 'auto' // mets 'smooth' après si tu veux
            });

        }
    "
    x-ref="container"        
    class="max-h-[500px] overflow-y-auto">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
    {{ $slot }}
    </div>
</div>