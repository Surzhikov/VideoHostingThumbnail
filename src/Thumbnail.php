<?php

namespace Surzhikov\VideoHostingThumbnail;

use \GuzzleHttp\Client as GuzzleHttpClient;

class Thumbnail
{
	private $url;

	public function __construct($url)
	{
		$this->url = $url;
	}

	public static function forUrl($url)
	{
		return new \Surzhikov\VideoHostingThumbnail\Thumbnail($url);

	}

	public function get()
	{
		// Test if it`s Youtube video
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->url, $match);
		if (array_key_exists(1, $match)) {
			$youtubeId = $match[1];
		}
		if (isset($youtubeId)) {
			return 'https://i.ytimg.com/vi/' . $youtubeId . '/hqdefault.jpg';
		}

		// Test if it`s Vimeo video
		preg_match('%(?:player.|www.)?vimeo\.com\/(?:video\/|embed\/|watch\?\S*v=|v\/)?(\d*)%i', $this->url, $match);
		if (array_key_exists(1, $match)) {
			$vimeoId = $match[1];
		}
		if (isset($vimeoId)) {
			$url = 'https://vimeo.com/api/v2/video/' . $vimeoId . '.json';
			$client = new GuzzleHttpClient;
			try {
				$vimeoMetaDataJson = $client->get($url, ['timeout' => 5])->getBody()->getContents();
				$vimeoMetaDataArray = json_decode($vimeoMetaDataJson, true);
			} catch (\Throwable $e){
				return null;
			}

			if (is_array($vimeoMetaDataArray) && array_key_exists(0, $vimeoMetaDataArray)) {
				if (array_key_exists('thumbnail_large', $vimeoMetaDataArray[0])) {
					return $vimeoMetaDataArray[0]['thumbnail_large'];
				}
			}
		}



		return null;
	}





}