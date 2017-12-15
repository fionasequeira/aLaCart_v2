import boto3
import json

def lambda_handler(event, context):
    fileName=str(event['search_value'])
    bucket='linefeed-images'
    client=boto3.client('rekognition','us-east-1')

    response = client.detect_labels(Image={'S3Object':{'Bucket':bucket,'Name':fileName}},MinConfidence=75)
    obj = []
    for label in response['Labels']:
        print (label['Name'] + ' : ' + str(label['Confidence']))
        obj.append(label['Name'])
    return {
        'statusCode': 200,
        'headers': { 'Content-Type': 'application/json' ,
            'Access-Control-Allow-Origin' : '*'
        },
        # 'body' : event["search_value"]
        'body': {'username' : obj}
    }