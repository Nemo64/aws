<?php

namespace AsyncAws\DynamoDbSession;


use AsyncAws\Core\Exception\Exception;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

class StandardSessionConnection implements SessionConnectionInterface
{
    use SessionConnectionConfigTrait;

    /** @var DynamoDbClient The DynamoDB client */
    protected $client;

    /**
     * @param DynamoDbClient    $client DynamoDB client
     * @param array             $config Session handler config
     */
    public function __construct(DynamoDbClient $client, array $config = [])
    {
        $this->client = $client;
        $this->initConfig($config);
    }

    public function read($id)
    {
        $item = [];

        try {
            // Execute a GetItem command to retrieve the item.
            $input = [
                'TableName' => $this->getTableName(),
                'Key' => $this->formatKey($id),
                'ConsistentRead' => $this->isConsistentRead(),
            ];
            $result = $this->client->getItem($input);

            // Get the item values
            /** @var AttributeValue[] $result */
            $result = $result->getItem() ?? [];

            $item[$this->getHashKey()] = $result[$this->getHashKey()]->getS();
            $type = $this->getDataAttributeType();
            if ($type == 'binary') {
                $item[$this->getDataAttribute()] = $result[$this->getDataAttribute()]->getB();
            } else {
                $item[$this->getDataAttribute()] = $result[$this->getDataAttribute()]->getS();
            }
            $item[$this->getSessionLifetimeAttribute()] = $result[$this->getSessionLifetimeAttribute()]->getN();

        } catch (Exception $e) {
            // Could not retrieve item, so return nothing.
        }

        return $item;
    }

    public function write($id, $data, $isChanged)
    {
        // Prepare the attributes
        $expires = time() + $this->getSessionLifetime();
        $attributes = [
            $this->getSessionLifetimeAttribute() => ['Value' => ['N' => (string) $expires]],
            'lock' => ['Action' => 'DELETE'],
        ];
        if ($isChanged) {
            if ($data != '') {
                $type = $this->getDataAttributeType();
                if ($type == 'binary') {
                    $attributes[$this->getDataAttribute()] = ['Value' => ['B' => $data]];
                } else {
                    $attributes[$this->getDataAttribute()] = ['Value' => ['S' => $data]];
                }

            } else {
                $attributes[$this->getDataAttribute()] = ['Action' => 'DELETE'];
            }
        }

        // Perform the UpdateItem command
        try {
            $input = [
                'TableName' => $this->getTableName(),
                'Key' => $this->formatKey($id),
                'AttributeUpdates' => $attributes,
            ];
            return $this->client->updateItem($input)->resolve();
        } catch (Exception $e) {
            return $this->triggerError("Error writing session $id: {$e->getMessage()}");
        }
    }

    public function delete($id)
    {
        try {
            $input = [
                'TableName' => $this->getTableName(),
                'Key' => $this->formatKey($id),
            ];
            return $this->client->deleteItem($input)->resolve();
        } catch (Exception $e) {
            return $this->triggerError("Error deleting session $id: {$e->getMessage()}");
        }
    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function formatKey($key)
    {
        return [$this->getHashKey() => ['S' => $key]];
    }

    /**
     * @param string $error
     *
     * @return bool
     */
    protected function triggerError($error)
    {
        trigger_error($error, E_USER_WARNING);

        return false;
    }
}
