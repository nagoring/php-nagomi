<?php
namespace Nago\Aws\S3;

class PutImageLogic
{
	protected $s3;
	protected $bucket;
	protected $url;
	public function __construct(\Aws\S3\S3Client $s3, $bucket) {
		$this->s3 = $s3;
		$this->bucket = $bucket;
		$this->url = '';
	}

	/**
	 * @param $src_filename
	 * @param $dest_filename
	 * @return $this
	 * @throw \Aws\S3\Exception\S3Exception
	 */
	public function run(string $src_filename, string $dest_filename){
		try {
			$content_type = mime_content_type($src_filename);
			/* @var \Aws\Result $awsResult */
			$awsResult = $this->s3->putObject([
				'Bucket' => $this->bucket,
				'Key'    => $dest_filename,
				'Body'   => fopen($src_filename, 'r'),
				'ACL'    => 'public-read',
				'ContentType' => $content_type,
			]);
		} catch (\Aws\S3\Exception\S3Exception $e) {
			throw $e;
		}
		$this->url = $awsResult['ObjectURL'];
		return $this;
	}
	public function getImageUrl() : string{
		return $this->url;
	}
}