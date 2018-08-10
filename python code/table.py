
import json
import datetime
import pandas as pd
from sqlalchemy import create_engine
from collections import Counter
import pprint as pprint


pd.set_option('display.max_rows', 500)
pd.set_option('display.max_columns', 500)
pd.set_option('display.width', 1000)

with open('bokking.json') as data_file:    
    data = json.load(data_file)
use_data = data[10]#35 38 46
#35 three type's of farebases in on farerule
#30 37 close
#20 open




fare_id = use_data['cid']
ticket_info = use_data['js_ticket']
load = json.loads(ticket_info)
user_info = load['passes']

print(fare_id)

user_dict = {'id': [],'name': [],'surname': [],'ticket_id': [],'total_price': [],'sum_tax':[],'without_tax': [],'sum_penalty': [],'refund':[]}
tax_dict = {'user_id': [], 'price': [],'tax_nature': [],'country_code': [],'Refund':[]}
dep_dict = {'user_id': [], 'status': [], 'dep_time': [], 'arr_time': [], 'from_loc': [], 'to_loc': [],'fare_basis': []}
last = {'id':[],'name': [],'surname': [],'ticket_id': [],'total_price': [],'sum_tax':[],'without_tax': [],'sum_penalty': [],'refund':[]}
dep_new_dict = {'user_id': [], 'status': [],'loc': [],'fare_basis': []}

arr = []
fare_list = []
amount_price = {}

def getUser(data):

    count = 1
    for i in data:
        user_dict['id'].append(count)
        user_dict['name'].append(i['GivenName'])
        user_dict['surname'].append(i['Surname'])
        user_dict['total_price'].append(int(i['TotalFare']))
        user_dict['ticket_id'].append(i['eTicketNumber'])
        user_dict['sum_tax'].append(0)
        user_dict['without_tax'].append(int(i['TotalFare']))
        user_dict['sum_penalty'].append(0)
        user_dict['refund'].append(int(i['TotalFare']))

        arr.append("")
        fare_list.append("")
        amount_price.update({count: 0})

        for route in i['Routes']:
            # pprint.pprint(route)
            arr_time = (datetime.datetime.strptime(route['ArrivalDate'], '%Y-%m-%dT%H:%M:%S'))
            dep_time = (datetime.datetime.strptime(route['DepartureDate'], '%Y-%m-%dT%H:%M:%S'))

            dep_dict['user_id'].append(count)
            dep_dict['status'].append(calc(route['DepartureDate']))
            dep_dict['dep_time'].append(route['DepartureDate'])
            dep_dict['arr_time'].append(arr_time)
            dep_dict['from_loc'].append(route['From'])
            dep_dict['to_loc'].append(route['To'])
            dep_dict['fare_basis'].append(route['FareBasis'])

            arr[count-1] += str(route['From'] + '-' +  route['To'] + ' ')
            fare_list[count-1] += (route['FareBasis'] + ' ')

        for tax in i['Taxes']:
            # pprint.pprint(i)
            tax_dict['user_id'].append(count)
            tax_dict['price'].append(int(tax['Amount']))
            tax_dict['tax_nature'].append(tax['TaxNature'])
            tax_dict['country_code'].append(tax['CountryCode'])
            tax_dict['Refund'].append('refundable')
        count += 1


    fare_rule = getFare(fare_list)


    print(arr)

    count_save = 0
    for i in range(0, (count-1)):
        dep_new_dict['user_id'].append(i+1)
        dep_new_dict['loc'].append(arr[count_save].strip())
        dep_new_dict['fare_basis'].append(' '.join(fare_rule[count_save]))
        dep_new_dict['status'].append(calc(dep_dict["dep_time"][0]))

        isExist = Checker(arr[count_save].strip(),  ' '.join(fare_rule[count_save] ),dep_dict)


        if len(isExist) == 0:
            # saveToSQL(user_df, tax_df, dep_df, saveRule())
            print('')
        else:
            tax_sum = getStructedTax(isExist['country_code'].strip(), amount_price)



            if len(tax_sum) != 0:
                sum_tax = tax_sum[i+1]
                penalty = ( (((int(user_dict['total_price'][i]) - tax_sum[i+1]) * isExist['penalty']) / 100) + isExist['penalty_price'])
                to_return = (int(user_dict['total_price'][i]) - penalty - tax_sum[i+1] )
                without_tax = (int(user_dict['total_price'][i]) - tax_sum[i+1])
            else:
                sum_tax = 0
                penalty = ((( int(user_dict['total_price'][i]) * isExist['penalty']) / 100) + isExist[
                    'penalty_price'])
                to_return = (int(user_dict['total_price'][i]) - penalty )
                without_tax = (int(user_dict['total_price'][i]))

            last['id'].append(i+1)
            last['name'].append(user_dict['name'][i])
            last['surname'].append(user_dict['surname'][i])
            last['ticket_id'].append(user_dict['ticket_id'][i])
            last['total_price'].append(int(user_dict['total_price'][i]))
            last['sum_tax'].append(sum_tax)
            last['without_tax'].append(without_tax)
            last['sum_penalty'].append(int(penalty))
            last['refund'].append(int(to_return))
        count_save += 1

    saveToSQL(user_dict, tax_dict, dep_new_dict, getRule())
    saveLastData(last)



## Checks same info exists or not
def Checker(segment, fare, dep_dict):
    engine = getConnection()
    con = engine.connect()
    execute = con.execute('select * from save_data;')
    a = {}
    if (calc(dep_dict['dep_time'][0]) == 'open' ):
        for i in execute:
            if (i['segment'].strip() == segment and (i['fare_basis'].strip()) == fare.strip() ):
                a = i
    return a



def getStructedTax(a, amount_price):
    for i in a.split(' '):
        amount = getTaxAmount(tax_dict, i)
        amount_price = dict(Counter(amount) + Counter(amount_price))
    return amount_price


##Tax Amount
def getTaxAmount(tax_dict,tax_sql):
    amount = {}

    for i in range (len(tax_dict['country_code'])):
        if (tax_dict['country_code'][i].strip() == tax_sql):
            amount[tax_dict['user_id'][i]] = 0

    for i in range (len(tax_dict['country_code'])):
        if (tax_dict['country_code'][i].strip() == tax_sql):
            tax_dict['Refund'][i] = 'non-refundable'
            tax_price = tax_dict['price'][i]
            amount[tax_dict['user_id'][i]] = amount[tax_dict['user_id'][i]] + tax_price
    return amount


##Connection with SQL
def getConnection():
  #  engine = create_engine("mysql+mysqlconnector://root:root@localhost/db_choco")
    engine = create_engine("mysql+mysqlconnector://root:@localhost/choco")
    return engine;


## To calculate status
def calc(dep_time):

    date_now_str = (datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
    dep_str = str(datetime.datetime.strptime(dep_time, '%Y-%m-%dT%H:%M:%S'))

    date_now = (datetime.datetime.strptime(date_now_str, '%Y-%m-%d %H:%M:%S'))
    dep_form = (datetime.datetime.strptime(dep_str, '%Y-%m-%d %H:%M:%S'))

    if dep_form > date_now:
        return "open"
    else:
        return "close"


    # return 'open'


def getFare(fare_list):
    temp = []
    for fare_l in range(0, len(fare_list)):
        try:
            a = fare_list[fare_l].split(' ')
            b = a[0:1]
            for i in range(len(fare_list[fare_l][:-1].split(' ')) - 1):
                if a[i] != a[i + 1]:
                    b.append(a[i + 1])
            a = b
            temp.append(a)
        except:
            temp.append(fare_list[fare_l])
    return temp



def getRule():
    rule_dict = {'rule_id': [], 'rule_text': [],'fare': []}
    with open('tarif.json') as data_file:
        rule = json.load(data_file)
    try:
        for all_data in rule:
            if (all_data['combination_id'] == fare_id):
                rules = all_data['tarif_xml']
                loads = json.loads(rules)

                for fares in loads['fares']:
                    rule_dict['fare'].append(fares['fare'])



                for i in loads['rules']:
                    for j in i:
                        if (j['rule_code'] == 'PE'):
                            rule_dict['rule_id'].append(all_data['combination_id'])
                            rule_dict['rule_text'].append(j['rule_text'])
        rule_df = pd.DataFrame(data = rule_dict)
    except:
        rule_df = pd.DataFrame(data=rule_dict)
    return rule_df


## To save into sql
def saveToSQL(user, tax, dep, rule):
    user_df = pd.DataFrame(data = user)
    dep_df = pd.DataFrame(data = dep)
    tax_df = pd.DataFrame(data = tax)

    print(user_df)
    print(dep_df)
    print(tax_df)

    # print(dep)

    engine = getConnection()

    tax_df.index.name = 'id'
    dep_df.index.name = 'id'

    user_df.to_sql(name = 'user_data',con = engine, if_exists = 'replace', index= False)
    tax_df.to_sql(name = 'tax_data',con = engine, if_exists = 'replace')
    dep_df.to_sql(name ='dep_data', con = engine, if_exists ='replace')
    rule.to_sql(name ='rule_data', con = engine, if_exists ='replace')

## Save automatic result
def saveLastData(last):
    print(last)
    last_df = pd.DataFrame(data = last)
    

    engine = getConnection()
    last_df.to_sql(name='last_data', con=engine, if_exists='replace',index = False)

getUser(user_info)