#```python
import random
import time
lower_bound = 1
upper_bound = 100
#```
print('Welcome to Guess Number-AI Edition!')
print('think of a number between',lower_bound, 'and', upper_bound)
time.sleep(2) 
print('I will try to guess any number you are thinking of.')
time.sleep(1)
print('You only need to respond with H if the guess is too high, L if the guess is too low and C if i guess correctly')
time.sleep(2) 
print ('lets get started')
#```python 
def generate_guess(lower_bound,upper_bound):
return random.randint(lower_bound,upper_bound)
#```python 
def get_user_feedback():
user_response = input ('is the guess correct? (H/L/C):').upper()
while user_response not in ['H','L','C']:
print ('invalid input Please enter 'H','L',or 'C' ')
user_response = input ('Is the guess correct?'(H/L/C):)upper()
return user_response
#```
#```python
def main ()
print('Think of a number' lower_bound, 'and', upper_bound)
time.sleep(1)
while True:
    guess=generate_guess(lower_bound,upper_bound)
    print('Ais guess:',guess)
    user_response =get_user_feedback()
    if user_response == 'H'
    upper_bound =guess-1
    elif user_response =='L'
    lower_bound = guess+1
    else:
        print ('Ai guessed correctly the number was ', guess)
        break
    #```
    if__name__ == '__main__':
    main()
#```
