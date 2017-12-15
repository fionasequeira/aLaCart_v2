import json
import urllib2
import ast
#from __future__ import print_function

def lambda_handler(event, context):
    message = ""
    t = []
    url = ""
    try:
        message = json.loads(event['body'])['search_value']
        #message.replace("\n","")
        for i in range(len(message)):
            t.append(message[i].replace("\n","").lower())
        #message = ast.literal_val(str(items)
    except Exception as e:
        message = e.message
    url =""
    url = "http://api.yummly.com/v1/api/recipes?_app_id=52f856ef&_app_key=3f317aebe40c9c9685e7b6c77239eb53"
    for item in t:
        url = url + "&allowedIngredient[]="+item
        
    
    
    
    return {
        'statusCode': 200,
        'headers': { 'Content-Type': 'application/json' ,
            'Access-Control-Allow-Origin' : '*'
        },
        'body': json.dumps({ 'username':  url}
        )
    }
