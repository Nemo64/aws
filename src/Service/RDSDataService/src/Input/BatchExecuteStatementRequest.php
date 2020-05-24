<?php

namespace AsyncAws\RDSDataService\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\RDSDataService\ValueObject\SqlParameter;

final class BatchExecuteStatementRequest extends Input
{
    /**
     * The name of the database.
     *
     * @var string|null
     */
    private $database;

    /**
     * @var array[]
     */
    private $parameterSets;

    /**
     * The Amazon Resource Name (ARN) of the Aurora Serverless DB cluster.
     *
     * @required
     *
     * @var string|null
     */
    private $resourceArn;

    /**
     * The name of the database schema.
     *
     * @var string|null
     */
    private $schema;

    /**
     * The name or ARN of the secret that enables access to the DB cluster.
     *
     * @required
     *
     * @var string|null
     */
    private $secretArn;

    /**
     * The SQL statement to run.
     *
     * @required
     *
     * @var string|null
     */
    private $sql;

    /**
     * The identifier of a transaction that was started by using the `BeginTransaction` operation. Specify the transaction
     * ID of the transaction that you want to include the SQL statement in.
     *
     * @var string|null
     */
    private $transactionId;

    /**
     * @param array{
     *   database?: string,
     *   parameterSets?: array[],
     *   resourceArn?: string,
     *   schema?: string,
     *   secretArn?: string,
     *   sql?: string,
     *   transactionId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->database = $input['database'] ?? null;
        $this->parameterSets = [];
        foreach ($input['parameterSets'] ?? [] as $key => $item) {
            $this->parameterSets[$key] = array_map([SqlParameter::class, 'create'], $item);
        }
        $this->resourceArn = $input['resourceArn'] ?? null;
        $this->schema = $input['schema'] ?? null;
        $this->secretArn = $input['secretArn'] ?? null;
        $this->sql = $input['sql'] ?? null;
        $this->transactionId = $input['transactionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @return array[]
     */
    public function getParameterSets(): array
    {
        return $this->parameterSets;
    }

    public function getResourceArn(): ?string
    {
        return $this->resourceArn;
    }

    public function getSchema(): ?string
    {
        return $this->schema;
    }

    public function getSecretArn(): ?string
    {
        return $this->secretArn;
    }

    public function getSql(): ?string
    {
        return $this->sql;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/BatchExecute';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDatabase(?string $value): self
    {
        $this->database = $value;

        return $this;
    }

    /**
     * @param array[] $value
     */
    public function setParameterSets(array $value): self
    {
        $this->parameterSets = $value;

        return $this;
    }

    public function setResourceArn(?string $value): self
    {
        $this->resourceArn = $value;

        return $this;
    }

    public function setSchema(?string $value): self
    {
        $this->schema = $value;

        return $this;
    }

    public function setSecretArn(?string $value): self
    {
        $this->secretArn = $value;

        return $this;
    }

    public function setSql(?string $value): self
    {
        $this->sql = $value;

        return $this;
    }

    public function setTransactionId(?string $value): self
    {
        $this->transactionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->database) {
            $payload['database'] = $v;
        }

        $index = -1;
        foreach ($this->parameterSets as $listValue) {
            ++$index;

            $index1 = -1;
            foreach ($listValue as $listValue) {
                ++$index1;
                $payload['parameterSets'][$index][$index1] = $listValue->requestBody();
            }
        }

        if (null === $v = $this->resourceArn) {
            throw new InvalidArgument(sprintf('Missing parameter "resourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['resourceArn'] = $v;
        if (null !== $v = $this->schema) {
            $payload['schema'] = $v;
        }
        if (null === $v = $this->secretArn) {
            throw new InvalidArgument(sprintf('Missing parameter "secretArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['secretArn'] = $v;
        if (null === $v = $this->sql) {
            throw new InvalidArgument(sprintf('Missing parameter "sql" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['sql'] = $v;
        if (null !== $v = $this->transactionId) {
            $payload['transactionId'] = $v;
        }

        return $payload;
    }
}
