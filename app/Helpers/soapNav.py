import re
from unittest import result
import requests
import json
from requests_ntlm import HttpNtlmAuth
from xml.etree.ElementTree import XML, fromstring
import sys
import argparse
def createParser ():
    parser = argparse.ArgumentParser()
    parser.add_argument ('-u', '--user', default='user')
    parser.add_argument ('-p', '--password', default='pass')
    parser.add_argument ('-l', '--url', default='link')
    parser.add_argument ('-a', '--action', default='action')
    parser.add_argument ('-f', '--filters', default='')
    return parser
# Символы фильтров замена
# ! = >
# @ = <
# ? = |
# # = &
def addFilters (filterString):
    res =''
    if filterString!='':
        filters = filterString.split(",")
        for filter in filters:
            filterAr = filter.split(":")
            filterAr[1] = filterAr[1].replace("!",">")
            filterAr[1] = filterAr[1].replace("@","<")
            filterAr[1] = filterAr[1].replace("?","|")
            filterAr[1] = filterAr[1].replace("#","&")
            res += "<filter><Field>"+filterAr[0]+"</Field><Criteria>"+filterAr[1]+"</Criteria></filter>"
    return res
if __name__ == '__main__':
    sys.stdout.reconfigure(encoding='utf-8')
    parser = createParser()
    namespace = parser.parse_args(sys.argv[1:])
    filters = addFilters(namespace.filters)
    headers = '{"soapAction":"urn:microsoft-dynamics-schemas/page/'+namespace.action+'","content-type":"text/xml; charset=utf-8"}'
    method = namespace.action.split(":")[1]
    space = namespace.action.split(":")[0]
    body = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/"><Body><'+method+' xmlns="urn:microsoft-dynamics-schemas/page/'+space+'">'+filters+'<setSize>300</setSize>'+'</'+method+'></Body></Envelope>'
    r = requests.post(namespace.url,data=body.encode('utf-8'), headers=json.loads(headers), auth=HttpNtlmAuth(namespace.user, namespace.password))
    print(r.text)