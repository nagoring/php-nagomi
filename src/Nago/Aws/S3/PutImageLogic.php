<?php
namespace Nago\Aws\S3;

class PutImageLogic
{
	protected $s3;
	protected $bucket;
	protected $url;

	/**
	 * PutImageLogic constructor.
	 * @param \Aws\S3\S3Client $s3
	 * @param $bucket
	 */
	public function __construct(\Aws\S3\S3Client $s3, $bucket) {
		$this->s3 = $s3;
		$this->bucket = $bucket;
		$this->url = '';
	}

	/**
	 * @param $src_filepath
	 * @param $dest_filepath
	 * @return $this
	 * @throw \Aws\S3\Exception\S3Exception
	 */
	public function run(string $src_filepath, string $dest_filepath){
		try {
			$fp = fopen($src_filepath, 'r');
			$content_type = mime_content_type($src_filepath);
			/* @var \Aws\Result $awsResult */
			$awsResult = $this->s3->putObject([
				'Bucket' => $this->bucket,
				'Key'    => $dest_filepath,
				'Body'   => $fp,
				'ACL'    => 'public-read',
				'ContentType' => $content_type,
			]);
		} catch (\Aws\S3\Exception\S3Exception $e) {
			throw $e;
		} finally {
			fclose($fp);
		}
		$this->url = $awsResult['ObjectURL'];
		return $this;
	}
	public function getImageUrl() : string{
		return $this->url;
	}
}