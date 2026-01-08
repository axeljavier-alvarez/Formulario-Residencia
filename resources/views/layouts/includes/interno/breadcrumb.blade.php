@if (count($breadcrumb) > 0)
<nav class="flex mb-4" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
    {{-- <li class="inline-flex items-center">
      <a href="#" class="inline-flex items-center text-sm font-medium text-body hover:text-fg-brand">
        <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/></svg>
        Home
      </a>
    </li> --}}

    @foreach($breadcrumb as $item)
        <li>
            <div class="flex items-center space-x-1.5">

                @if ($loop->first)
                        <svg class="w-4 h-4 me-1.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/></svg>

                @else

                 <svg class="w-3.5 h-3.5 rtl:rotate-180 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
                 
                @endif


                


                <!-- isset nos ayuda a ver si hay algo definido de nuestro programa-->

                @isset($item['url'])
                <a href="{{ $item['url'] }}"
                   class="{{ $loop->first
                        ? 'text-blue-600 underline font-bold hover:text-blue-800'
                        : 'inline-flex items-center font-bold  text-body-subtle'

                    
                   }}"

                   style="{{ !$loop->first ? 'color:#4FA74F' : '' }}"
                   >
                    {{ $item['name'] }}
                </a>
            @else
                <span class="inline-flex items-center textfont-medium font-bold text-body-subtle" style="color:#4FA74F">
                    {{ $item['name'] }}
                </span>
            @endisset

               
             




            </div>
        </li>
    @endforeach
    
    {{-- <li aria-current="page">
      <div class="flex items-center space-x-1.5">
        <svg class="w-3.5 h-3.5 rtl:rotate-180 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
        <span class="inline-flex items-center text-sm font-medium text-body-subtle">Flowbite</span>
      </div>
    </li> --}}
  </ol>
</nav>


@endif
