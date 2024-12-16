<div class="acronim">
    <strong>
        {{ $thesisId }}
    </strong>
</div>
<div class="title">
    <strong>{!! $title !!}</strong>
</div>

<div class="authors">
    @foreach ($authors as $key => $author)
        @php
            $authorAffiliationIndexes = [];
            foreach ($author['affiliations'] ?? [] as $affiliation) {
                if (isset($affiliation['title_' . $lang])) {
                    if (
                        $affiliationsList->contains(
                            fn($value) => $affiliation['title_' . $lang] === $value['title_' . $lang],
                        )
                    ) {
                        $index = $affiliationsList->search(
                            fn($val) => $val['title_' . $lang] === $affiliation['title_' . $lang],
                        );
                        $authorAffiliationIndexes[] = $index + 1;
                    }
                }
            }
        @endphp
        @if ($reporter['id'] == $key)
            <strong>
        @endif
        <span>
            {{ $author['name_' . $lang] }}@if (!empty($author['middle_name_' . $lang]))
                {{ mb_substr($author['middle_name_' . $lang], 0, 1) }}.
            @endif {{ $author['surname_' . $lang] }}<sup
                class="sup">{{ implode(',', $authorAffiliationIndexes) }}</sup>
            @if (!$loop->last)
                ,
            @endif
        </span>
        @if ($reporter['id'] == $key)
            </strong>
        @endif
    @endforeach
</div>
<ul class="affiliations-list">
    @foreach ($affiliationsList as $key => $affiliation)
        @if ($affiliation['no_affiliation'] && isset($affiliation['country']["name_$lang"]))
            <li class="">
                <sup>{{ $key + 1 }}</sup>
                {{ $affiliation['title_' . $lang] }}@if ($affiliation['no_affiliation'])
                    , {{ $affiliation['country']["name_$lang"] }}
                @endif
            </li>
        @else
            <li class="">
                <sup>{{ $key + 1 }}</sup>
                {{ $affiliation['title_' . $lang] }}
            </li>
        @endif
    @endforeach
</ul>

@if ($solicitedTalk)
	<div style="color:#e25553; margin-bottom: 10px" class="text">Solicited talk</div>
@endif

<div class="email">{{ $contact['email'] }}</div>
<div class="text">{!! str($text)->replace('<br>', ' ') !!}</div>
