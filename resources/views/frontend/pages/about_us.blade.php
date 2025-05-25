@extends('frontend.layouts.app')

@section('content')
    @php
        $about_us = \App\Models\Page::where('type', 'about_page')->first();

        $words = str_word_count(strip_tags($about_us->getTranslation('content')), 1);
        $wordCount = count($words); // Get the total number of words

        $midpoint = ceil($wordCount / 2) + 54; // Calculate the midpoint

        $firstHalf = array_slice($words, 0, $midpoint);
        $secondHalf = array_slice($words, $midpoint);
    @endphp
    <section class="pt-4 mb-4">
        <div class="container text-center">

        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img id="about_us_image" width="100%" src="{{ uploaded_asset($about_us->image) }}" alt="">
                </div>
                @if ($wordCount > 335)
                    <div class="col-md-6">
                        <div class="my__title about___title">
                            <h1 class="text-uppercase text-left d-inline-block border-bottom">{{ translate('About Us') }}</h1>
                        </div>
                        <div id="about_us_content_column" class="text-justify">
                            @foreach ($firstHalf as $word)
                                {{ $word }}
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-12 mt-2" class="text-justify">
                        @foreach ($secondHalf as $word)
                            {{ $word }}
                        @endforeach
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="my__title about___title">
                            <h1 class="text-uppercase text-left d-inline-block border-bottom">{{ translate('About Us') }}</h1>
                        </div>
                        <div id="about_us_content_column" class="text-justify">
                            {!! $about_us->getTranslation('content') !!}
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </section>

    {{-- <script>
        window.onload = function() {
            var image = document.getElementById('about_us_image');
            var contentColumn = document.getElementById('about_us_content_column');


            if (contentColumn.offsetHeight <= image.height) {
                contentColumn.classList.add('col-md-6');
            } else if (contentColumn.offsetHeight >= image.height) {

                contentColumn.classList.add('col-md-12');
            }
        };
    </script> --}}
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
        }

        #about_us_content_column {
            flex-grow: 1;
            margin-left: 20px;
            /* Adjust as needed */
        }

        #about_us_image {
            width: 100%;
        }

        @media (max-width: 992px) {
            #about_us_content_column {
                margin-left: 0;
                /* Reset margin for smaller screens */
            }
        }
    </style>
@endsection
