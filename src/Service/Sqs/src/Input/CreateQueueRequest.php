<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\QueueAttributeName;

final class CreateQueueRequest extends Input
{
    /**
     * The name of the new queue. The following limits apply to this name:.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueName;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var array<QueueAttributeName::*, string>
     */
    private $Attributes;

    /**
     * Add cost allocation tags to the specified Amazon SQS queue. For an overview, see Tagging Your Amazon SQS Queues in
     * the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-tags.html
     *
     * @var array<string, string>
     */
    private $tags;

    /**
     * @param array{
     *   QueueName?: string,
     *   Attributes?: array<\AsyncAws\Sqs\Enum\QueueAttributeName::*, string>,
     *   tags?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueName = $input['QueueName'] ?? null;
        $this->Attributes = $input['Attributes'] ?? [];
        $this->tags = $input['tags'] ?? [];
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<QueueAttributeName::*, string>
     */
    public function getAttributes(): array
    {
        return $this->Attributes;
    }

    public function getQueueName(): ?string
    {
        return $this->QueueName;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'CreateQueue', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<QueueAttributeName::*, string> $value
     */
    public function setAttributes(array $value): self
    {
        $this->Attributes = $value;

        return $this;
    }

    public function setQueueName(?string $value): self
    {
        $this->QueueName = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->QueueName) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueName'] = $v;

        $index = 0;
        foreach ($this->Attributes as $mapKey => $mapValue) {
            if (!QueueAttributeName::exists($mapKey)) {
                throw new InvalidArgument(sprintf('Invalid key for "%s". The value "%s" is not a valid "QueueAttributeName".', __CLASS__, $mapKey));
            }
            ++$index;
            $payload["Attribute.$index.Name"] = $mapKey;
            $payload["Attribute.$index.Value"] = $mapValue;
        }

        $index = 0;
        foreach ($this->tags as $mapKey => $mapValue) {
            ++$index;
            $payload["Tag.$index.Key"] = $mapKey;
            $payload["Tag.$index.Value"] = $mapValue;
        }

        return $payload;
    }
}
