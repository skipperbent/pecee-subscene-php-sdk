# pecee/subscene-php-sdk

Simple, lightweight PHP-SDK for searching and downloading subtitles from [Subscene.com](https://www.subscene.com).

#### WARNING: USE WITH CAUTION!

Unfortunately Subscene.com does not yet provide an official API, even though they announced one on Twitter back in 2013.

This means, that the SDK is subject to change and might not work as expected, when/if new updates are applied to their website.

Please keep that in mind before using this SDK in production.

#### Credits

Thanks to [Subscene.com](https://www.subscene.com) for providing that awesome service!

We're still waiting for that API [you promised](https://twitter.com/subscene_com/status/367914801315340288?lang=en) :)

# Installation

You can add this library to your project easily by running the following composer command in your terminal/command-line:

```php
composer require pecee/subscene-php-sdk
```

### Requirements

- PHP 5.6 or greater
- curl extension enabled

# Usage

- **[Search for subtitles](#search-for-subtitles)**
   - [Parameters](#parameters)
   - [Response](#response)
   
- **[Get subtitles by movie id](#get-subtitles-by-movie-id)**
   - [Parameters](#parameters-1)
   - [Response](#response-1)
   
- **[Full search](#full-search)**
   - [Parameters](#parameters-2)
   - [Response](#response-2)
   

## Search for subtitles

```php
$client = \Pecee\Service\Subscene();
$subtitles = $movie->search('The Walk');
```

### Parameters

| Name              | Type          | Default value | Description                                                                               |
| -------------     |----------     | -----         | ---------------                                                                           |
| `$title`          | `string`      |               | Title of the movie you want to search for.                                                |
| `$languageIds`    | `array\|null`  | `null`        | The language-ids you want subtitles for. Example: `dan`, `eng`, `pol` etc.                |

### Response

```json
[
   {
      "id":"the-walk",
      "title":"The Walk",
      "year":"2015",
      "count":"114",
   }
]
```

## Get subtitles by movie id

```php
$client = \Pecee\Service\Subscene();
$subtitles = $movie->getSubtitles('the-walk', ['dan', 'eng']);
```

### Parameters

| Name              | Type          | Default value | Description                                                                               |
| -------------     |----------     | -----         | ---------------                                                                           |
| `$id`             | `string`      |               | Unique identifier/id for the movie you want to get the subtitles for.                     |
| `$languageIds`    | `array\|null`  | `null`        | The language-ids you want subtitles for. Example: `dan`, `eng`, `pol` etc.                |
| `$multiple`       | `bool`        | `false`       | Fetch multiple subtitles per language. Can be slow depending on the number of results.    |

### Response

```json
[
   {
      "id":"the-walk",
      "title":"The Walk",
      "year":"2015",
      "count":"114",
      "subtitles":[
         {
            "language":"Danish",
            "language_id":"dan",
            "filename":"The-Walk-2015-720p-BluRay-x264-YIFY-[YTS.AG]",
            "author":"Firewalker.dk",
            "comment":"Retail Rippet og tilpasset af TeamSky uploadet af Firewalker.dk",
            "download_url":"https:\/\/subscene.com\/subtitle\/download?mac=Hd63BOWXFRbtZJKQmd1nmRLJ9cJAYJeYQYidqyixTQ5ORYDQ72_lUCwgSWx9Vdd28ODVS7GlWY0gMzo58gDZsKDEkQf5UgrlJKf8PL_LJHxYCc6rFPaePaE7UfGlSgJL0"
         },
         {
            "language":"English",
            "language_id":"eng",
            "filename":"Yify \/ Superchillin",
            "author":"japangoodtime",
            "comment":"French speaking parts and some hard to hear English part",
            "download_url":"https:\/\/subscene.com\/subtitle\/download?mac=YYhV9nVUcbV1K_BhkVM8E6BrGB8qmFEFX3ykn6Iz0JEwNzh2YsugBFp-18ue_eYJw3RE_fVmlHV8nQh_FLsy7r5kZIJ_Z3OTBDIfWhsj2BhhcKnFrY8jXQycuHUOuiSS0"
         }
      ]
   }
]
```

## Full search

This method will lookup subtitles for each result. Can be extremely slow depending on the number of results.

```php
$client = \Pecee\Service\Subscene();
$subtitles = $movie->searchFull('the-walk', ['dan', 'eng']);
```

### Parameters

| Name              | Type          | Default value | Description                                                                               |
| -------------     |----------     | -----         | ---------------                                                                           |
| `$title`          | `string`      |               | Title of the movie you want to search for.                                                |
| `$languageIds`    | `array\|null`  | `null`        | The language-ids you want subtitles for. Example: `dan`, `eng`, `pol` etc.                |
| `$multiple`       | `bool`        | `false`       | Fetch multiple subtitles per language. Can be slow depending on the number of results.    |

### Response:

```json
[
   {
      "id":"the-walk",
      "title":"The Walk",
      "year":"2015",
      "count":"114",
      "subtitles":[
         {
            "language":"Danish",
            "language_id":"dan",
            "filename":"The-Walk-2015-720p-BluRay-x264-YIFY-[YTS.AG]",
            "author":"Firewalker.dk",
            "comment":"Retail Rippet og tilpasset af TeamSky uploadet af Firewalker.dk",
            "download_url":"https:\/\/subscene.com\/subtitle\/download?mac=Hd63BOWXFRbtZJKQmd1nmRLJ9cJAYJeYQYidqyixTQ5ORYDQ72_lUCwgSWx9Vdd28ODVS7GlWY0gMzo58gDZsKDEkQf5UgrlJKf8PL_LJHxYCc6rFPaePaE7UfGlSgJL0"
         },
         {
            "language":"English",
            "language_id":"eng",
            "filename":"Yify \/ Superchillin",
            "author":"japangoodtime",
            "comment":"French speaking parts and some hard to hear English part",
            "download_url":"https:\/\/subscene.com\/subtitle\/download?mac=YYhV9nVUcbV1K_BhkVM8E6BrGB8qmFEFX3ykn6Iz0JEwNzh2YsugBFp-18ue_eYJw3RE_fVmlHV8nQh_FLsy7r5kZIJ_Z3OTBDIfWhsj2BhhcKnFrY8jXQycuHUOuiSS0"
         }
      ]
   }
]
```

# License

### The MIT License (MIT)

Copyright (c) 2017 Simon Sessingø / subscene-php-sdk

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
