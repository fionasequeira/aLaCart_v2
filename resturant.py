#from future_ import print_function
#import tweepy
#import boto3
import urllib2
import json
#from datetime import datetime

#client_id_value = "HGQ1XH1SLIPATOTOBODASNZ2RKCKE0VIMVH2REPPYD5SVMYL"
#client_secret_value = "QUIXHSWEDWG5YLRT44YIN0WCEL0PLNH00BS2U1YKT5VVTPCS"

foursquare_base = "https://api.foursquare.com/v2/venues/search?v=20161016&query=restaurant&client_id=HGQ1XH1SLIPATOTOBODASNZ2RKCKE0VIMVH2REPPYD5SVMYL&client_secret=QUIXHSWEDWG5YLRT44YIN0WCEL0PLNH00BS2U1YKT5VVTPCS&near="

near = '%20Park'

foursquare_request = foursquare_base + near

x = urllib2.urlopen(foursquare_request)

response = x.read().decode('utf-8')

response = json.loads(response)

venues = response['response']['venues']

#print(venues)
data={}
json_data = []


for venue in venues:
    try:
        data['name'] = venue['name']
        data['lat'] = venue['location']['lat']
        data['lng'] = venue['location']['lng']
        data['address'] = venue['location']['address']
        data['contact'] = venue['contact']['formattedPhone']
    except:
        pass    
    try:
        data['url'] = venue['stats']['url']
    except:
        data['url'] = 'Does not Exist'
    try:
        data['menu'] = venue['menu']['url']
    except:
        data['menu'] = 'Does not Exist'
    #print(data)
    okay="{\"url\":\""+data['menu']+"\",\"lat\":\""+str(data['lat'])+"\",\"long\":\""+str(data['lng'])+"\",\"name\":\""+ data['name']+"\"}"
    print(okay)
    '''  
    try:
        req=urllib2.urlopen("https://search-messi-kkvckwfx7xc3at4b4r2ilv2tii.us-east-1.es.amazonaws.com/resta/goo",data=okay)    
    except Exception as e:
        print(e.message)

    ''' 
    try:
        request = urllib2.Request("https://search-messi-kkvckwfx7xc3at4b4r2ilv2tii.us-east-1.es.amazonaws.com/fiona/goo", headers={"content-type" : "application/JSON"})
        contents = urllib2.urlopen(request,data=okay).read()
        a = 1
    except Exception as e:
        print (e.message )   
    #print contents   
#req=urllib2.urlopen("https://search-messi-kkvckwfx7xc3at4b4r2ilv2tii.us-east-1.es.amazonaws.com/resta/goo",data=okay)
