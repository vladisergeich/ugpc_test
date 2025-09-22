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
    parser.add_argument ('-c', '--okvid', default='okvid')
    return parser
if __name__ == '__main__':
    sys.stdout.reconfigure(encoding='utf-8')
    parser = createParser()
    namespace = parser.parse_args(sys.argv[1:])
    body = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/"><Body><OrderMetr xmlns="urn:microsoft-dynamics-schemas/codeunit/WS_Global"><p_okvid>'+ namespace.okvid +'</p_okvid></OrderMetr></Body></Envelope>'
    headers = '{"soapAction":"urn:microsoft-dynamics-schemas/codeunit/WS_Global:OrderMetr","content-type":"text/xml; charset=utf-8"}'
    r = requests.post(namespace.url,data=body.encode('utf-8'), headers=json.loads(headers), auth=HttpNtlmAuth(namespace.user, namespace.password))
    print(r.text)
    # python3 customerInventory.py -u username -p pass -l http://sqlserver.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Codeunit/WS_Global -c КЛ769