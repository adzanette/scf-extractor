
from modules.Configuration import *
from command import *

command = eval(config.command+"()")
command.run()


#from evaluator import Evaluator
#evaluator = Evaluator()

#evaluator.verbHistogram('get', cutoff=50, output = '/home/adriano/Desktop/teste.png')

#evaluator.evaluate('frel', 0.2, 0.01, 0, 10, 10)
#evaluator.plot()

#verbList = ['go', 'be', 'do', 'have', 'put']
#evaluator.evaluateByVerbList(verbList)


# bht 
# remover pontos fora da curva do powerlaw
# valex como gold
# bnc nlpserver
