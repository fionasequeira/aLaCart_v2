import json
import boto3
def lambda_handler(event, context):
    client = boto3.client('sns',aws_access_key_id='hahahahahahahahah',aws_secret_access_key='hahahahahahaha',region_name='hahahahahaha')
    phone = "+15168154791"
    message = "awesome"
    foo  =""
    t = []
    try:
        foo = json.loads(event['body'])
        message = foo['search_fridge']
        phone = foo['search_number']
        
        for i in range(len(message)):
            t.append(message[i].replace("\n","").lower())
        
        val = ""
        for i in t:
            val = val+i+", "
        client.publish(PhoneNumber = phone, Message=val)   
        foo = event['body']
    except Exception as e:
        message = e.message
    return {
        'statusCode': 200,
        'headers': { 'Content-Type': 'application/json' ,
            'Access-Control-Allow-Origin' : '*'
        },
        'body': json.dumps({ 'username':  foo, 'id': 20 }
        )
        }