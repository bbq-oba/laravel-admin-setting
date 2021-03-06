<div {!! $attributes !!}>
    <ul class="nav nav-tabs nav-tabs">

        @foreach($tabs as $id => $tab)
            @if($tab['type'] == \Encore\Admin\Widgets\Tab::TYPE_CONTENT)
                <li {{ $id == $active ? 'class=active' : '' }}><a href="#tab_{{ $tab['id'] }}"
                                                                  data-toggle="tab">{{ $tab['title'] }}</a></li>
            @elseif($tab['type'] == \Encore\Admin\Widgets\Tab::TYPE_LINK)
                <li {{ $id == $active ? 'class=active' : '' }}><a href="{{ $tab['href'] }}">{{ $tab['title'] }}</a></li>
            @endif
        @endforeach

        @if (!empty($dropDown))
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Dropdown <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($dropDown as $link)
                        <li role="presentation"><a role="menuitem" tabindex="-1"
                                                   href="{{ $link['href'] }}">{{ $link['name'] }}</a></li>
                    @endforeach
                </ul>
            </li>
        @endif
        @if (!empty($button))
            <li class="pull-right">
                <div class="btn-group pull-right" style="margin-top: 3px;">
                    @foreach($button as $link)
                        <a class="btn btn-sm btn-success"
                           href="{{ $link['href'] }}">
                            <i class="{{ $link['icon'] }}"></i><span
                                class="hidden-xs"> {{$link['name']}}</span></a>
                    @endforeach
                </div>
            </li>
        @endif

    </ul>
    <div class="tab-content">
        @foreach($tabs as $id => $tab)
            <div class="tab-pane {{ $id == $active ? 'active' : '' }}" id="tab_{{ $tab['id'] }}">
                @php($content = \Illuminate\Support\Arr::get($tab, 'content'))
                @if($content instanceof \Illuminate\Contracts\Support\Renderable)
                    {!! $content->render() !!}
                @else
                    {!! $content !!}
                @endif
            </div>
        @endforeach

    </div>
</div>
