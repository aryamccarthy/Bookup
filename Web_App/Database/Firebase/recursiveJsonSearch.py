__author__ = 'DRizzuto'

def find_key(dic, key_match, valued=[]):
    keys=[]
    if isinstance(dic,dict):

        for key,value in dic.items():

            if isinstance(value,dict):
                keys.append(key)
                keys.append(find_key(value, key_match, valued))

            elif isinstance(value,list):
                keys.append(key)
                keys.append(find_key(value[0], key_match, valued))
                if key == key_match:
                    valued.append(value)

            else:
                keys.append(key)
                if key == key_match:
                    valued.append(value)

    return valued # in line with for loop
