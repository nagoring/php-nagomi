<?php
declare(strict_types=1);

namespace Nago\Aws\S3;


class DeleteImageLogic
{
	protected $s3;
	protected $bucket;
	protected $url;

	/**
	 * DeleteImageLogic constructor.
	 * @param \Aws\S3\S3Client $s3
	 * @param $bucket
	 */
	public function __construct(\Aws\S3\S3Client $s3, $bucket) {
		$this->s3 = $s3;
		$this->bucket = $bucket;
		$this->url = '';
	}

	/**
	 * @param string $filepath
	 * @return $this
	 */
	public function run(string $filepath){
		try {
			/* @var \Aws\Result $awsResult */
			$awsResult = $this->s3->deleteObject([
				'Bucket' => $this->bucket,
				'Key'    => $filepath,
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