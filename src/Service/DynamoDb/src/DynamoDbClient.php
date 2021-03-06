<?php

namespace AsyncAws\DynamoDb;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;
use AsyncAws\DynamoDb\Input\CreateTableInput;
use AsyncAws\DynamoDb\Input\DeleteItemInput;
use AsyncAws\DynamoDb\Input\DeleteTableInput;
use AsyncAws\DynamoDb\Input\DescribeTableInput;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\Input\ListTablesInput;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\Input\UpdateItemInput;
use AsyncAws\DynamoDb\Input\UpdateTableInput;
use AsyncAws\DynamoDb\Result\CreateTableOutput;
use AsyncAws\DynamoDb\Result\DeleteItemOutput;
use AsyncAws\DynamoDb\Result\DeleteTableOutput;
use AsyncAws\DynamoDb\Result\DescribeTableOutput;
use AsyncAws\DynamoDb\Result\GetItemOutput;
use AsyncAws\DynamoDb\Result\ListTablesOutput;
use AsyncAws\DynamoDb\Result\PutItemOutput;
use AsyncAws\DynamoDb\Result\QueryOutput;
use AsyncAws\DynamoDb\Result\ScanOutput;
use AsyncAws\DynamoDb\Result\TableExistsWaiter;
use AsyncAws\DynamoDb\Result\TableNotExistsWaiter;
use AsyncAws\DynamoDb\Result\UpdateItemOutput;
use AsyncAws\DynamoDb\Result\UpdateTableOutput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

class DynamoDbClient extends AbstractApi
{
    /**
     * The `CreateTable` operation adds a new table to your account. In an AWS account, table names must be unique within
     * each Region. That is, you can have two tables with same name if you create the tables in different Regions.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#createtable
     *
     * @param array{
     *   AttributeDefinitions: \AsyncAws\DynamoDb\ValueObject\AttributeDefinition[],
     *   TableName: string,
     *   KeySchema: \AsyncAws\DynamoDb\ValueObject\KeySchemaElement[],
     *   LocalSecondaryIndexes?: \AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex[],
     *   GlobalSecondaryIndexes?: \AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndex[],
     *   BillingMode?: \AsyncAws\DynamoDb\Enum\BillingMode::*,
     *   ProvisionedThroughput?: \AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput|array,
     *   StreamSpecification?: \AsyncAws\DynamoDb\ValueObject\StreamSpecification|array,
     *   SSESpecification?: \AsyncAws\DynamoDb\ValueObject\SSESpecification|array,
     *   Tags?: \AsyncAws\DynamoDb\ValueObject\Tag[],
     *   @region?: string,
     * }|CreateTableInput $input
     */
    public function createTable($input): CreateTableOutput
    {
        $input = CreateTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateTable', 'region' => $input->getRegion()]));

        return new CreateTableOutput($response);
    }

    /**
     * Deletes a single item in a table by primary key. You can perform a conditional delete operation that deletes the item
     * if it exists, or if it has an expected attribute value.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deleteitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   Expected?: array<string, \AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue>,
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ReturnValues?: \AsyncAws\DynamoDb\Enum\ReturnValue::*,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: \AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics::*,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   @region?: string,
     * }|DeleteItemInput $input
     */
    public function deleteItem($input): DeleteItemOutput
    {
        $input = DeleteItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteItem', 'region' => $input->getRegion()]));

        return new DeleteItemOutput($response);
    }

    /**
     * The `DeleteTable` operation deletes a table and all of its items. After a `DeleteTable` request, the specified table
     * is in the `DELETING` state until DynamoDB completes the deletion. If the table is in the `ACTIVE` state, you can
     * delete it. If a table is in `CREATING` or `UPDATING` states, then DynamoDB returns a `ResourceInUseException`. If the
     * specified table does not exist, DynamoDB returns a `ResourceNotFoundException`. If table is already in the `DELETING`
     * state, no error is returned.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deletetable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DeleteTableInput $input
     */
    public function deleteTable($input): DeleteTableOutput
    {
        $input = DeleteTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteTable', 'region' => $input->getRegion()]));

        return new DeleteTableOutput($response);
    }

    /**
     * Returns information about the table, including the current status of the table, when it was created, the primary key
     * schema, and any indexes on the table.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#describetable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DescribeTableInput $input
     */
    public function describeTable($input): DescribeTableOutput
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion()]));

        return new DescribeTableOutput($response);
    }

    /**
     * The `GetItem` operation returns a set of attributes for the item with the given primary key. If there is no matching
     * item, `GetItem` does not return any data and there will be no `Item` element in the response.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#getitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   AttributesToGet?: string[],
     *   ConsistentRead?: bool,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   ProjectionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   @region?: string,
     * }|GetItemInput $input
     */
    public function getItem($input): GetItemOutput
    {
        $input = GetItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetItem', 'region' => $input->getRegion()]));

        return new GetItemOutput($response);
    }

    /**
     * Returns an array of table names associated with the current account and endpoint. The output from `ListTables` is
     * paginated, with each page returning a maximum of 100 table names.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#listtables
     *
     * @param array{
     *   ExclusiveStartTableName?: string,
     *   Limit?: int,
     *   @region?: string,
     * }|ListTablesInput $input
     *
     * @return \Traversable<string> & ListTablesOutput
     */
    public function listTables($input = []): ListTablesOutput
    {
        $input = ListTablesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTables', 'region' => $input->getRegion()]));

        return new ListTablesOutput($response, $this, $input);
    }

    /**
     * Creates a new item, or replaces an old item with a new item. If an item that has the same primary key as the new item
     * already exists in the specified table, the new item completely replaces the existing item. You can perform a
     * conditional put operation (add a new item if one with the specified primary key doesn't exist), or replace an
     * existing item if it has certain attribute values. You can return the item's attribute values in the same operation,
     * using the `ReturnValues` parameter.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#putitem
     *
     * @param array{
     *   TableName: string,
     *   Item: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   Expected?: array<string, \AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue>,
     *   ReturnValues?: \AsyncAws\DynamoDb\Enum\ReturnValue::*,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: \AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics::*,
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   @region?: string,
     * }|PutItemInput $input
     */
    public function putItem($input): PutItemOutput
    {
        $input = PutItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutItem', 'region' => $input->getRegion()]));

        return new PutItemOutput($response);
    }

    /**
     * The `Query` operation finds items based on primary key values. You can query any table or secondary index that has a
     * composite primary key (a partition key and a sort key).
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#query
     *
     * @param array{
     *   TableName: string,
     *   IndexName?: string,
     *   Select?: \AsyncAws\DynamoDb\Enum\Select::*,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   ConsistentRead?: bool,
     *   KeyConditions?: array<string, \AsyncAws\DynamoDb\ValueObject\Condition>,
     *   QueryFilter?: array<string, \AsyncAws\DynamoDb\ValueObject\Condition>,
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ScanIndexForward?: bool,
     *   ExclusiveStartKey?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   KeyConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   @region?: string,
     * }|QueryInput $input
     *
     * @return \Traversable<array<string, AttributeValue>> & QueryOutput
     */
    public function query($input): QueryOutput
    {
        $input = QueryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Query', 'region' => $input->getRegion()]));

        return new QueryOutput($response, $this, $input);
    }

    /**
     * The `Scan` operation returns one or more items and item attributes by accessing every item in a table or a secondary
     * index. To have DynamoDB return fewer items, you can provide a `FilterExpression` operation.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#scan
     *
     * @param array{
     *   TableName: string,
     *   IndexName?: string,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   Select?: \AsyncAws\DynamoDb\Enum\Select::*,
     *   ScanFilter?: array<string, \AsyncAws\DynamoDb\ValueObject\Condition>,
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ExclusiveStartKey?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   TotalSegments?: int,
     *   Segment?: int,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   ConsistentRead?: bool,
     *   @region?: string,
     * }|ScanInput $input
     *
     * @return \Traversable<array<string, AttributeValue>> & ScanOutput
     */
    public function scan($input): ScanOutput
    {
        $input = ScanInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Scan', 'region' => $input->getRegion()]));

        return new ScanOutput($response, $this, $input);
    }

    /**
     * Check status of operation describeTable.
     *
     * @see describeTable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DescribeTableInput $input
     */
    public function tableExists($input): TableExistsWaiter
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion()]));

        return new TableExistsWaiter($response, $this, $input);
    }

    /**
     * Check status of operation describeTable.
     *
     * @see describeTable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DescribeTableInput $input
     */
    public function tableNotExists($input): TableNotExistsWaiter
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion()]));

        return new TableNotExistsWaiter($response, $this, $input);
    }

    /**
     * Edits an existing item's attributes, or adds a new item to the table if it does not already exist. You can put,
     * delete, or add attribute values. You can also perform a conditional update on an existing item (insert a new
     * attribute name-value pair if it doesn't exist, or replace an existing name-value pair if it has certain expected
     * attribute values).
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updateitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   AttributeUpdates?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValueUpdate>,
     *   Expected?: array<string, \AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue>,
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ReturnValues?: \AsyncAws\DynamoDb\Enum\ReturnValue::*,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: \AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics::*,
     *   UpdateExpression?: string,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   @region?: string,
     * }|UpdateItemInput $input
     */
    public function updateItem($input): UpdateItemOutput
    {
        $input = UpdateItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateItem', 'region' => $input->getRegion()]));

        return new UpdateItemOutput($response);
    }

    /**
     * Modifies the provisioned throughput settings, global secondary indexes, or DynamoDB Streams settings for a given
     * table.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updatetable
     *
     * @param array{
     *   AttributeDefinitions?: \AsyncAws\DynamoDb\ValueObject\AttributeDefinition[],
     *   TableName: string,
     *   BillingMode?: \AsyncAws\DynamoDb\Enum\BillingMode::*,
     *   ProvisionedThroughput?: \AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput|array,
     *   GlobalSecondaryIndexUpdates?: \AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexUpdate[],
     *   StreamSpecification?: \AsyncAws\DynamoDb\ValueObject\StreamSpecification|array,
     *   SSESpecification?: \AsyncAws\DynamoDb\ValueObject\SSESpecification|array,
     *   ReplicaUpdates?: \AsyncAws\DynamoDb\ValueObject\ReplicationGroupUpdate[],
     *   @region?: string,
     * }|UpdateTableInput $input
     */
    public function updateTable($input): UpdateTableOutput
    {
        $input = UpdateTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateTable', 'region' => $input->getRegion()]));

        return new UpdateTableOutput($response);
    }

    protected function getServiceCode(): string
    {
        return 'dynamodb';
    }

    protected function getSignatureScopeName(): string
    {
        return 'dynamodb';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
