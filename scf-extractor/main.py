"""
from modules.Configuration import *
from command import *

command = eval(config.command+"()")
command.run()

"""
from evaluator import Evaluator
evaluator = Evaluator()

#evaluator.verbHistogram('get', cutoff=50, output = '/home/adriano/Desktop/teste.png')

evaluator.evaluate('frel', 0.02, 0.001, 0, 1, 100)
evaluator.plot()

# bht 
# remover pontos fora da curva do powerlaw
# valex como gold
# bnc nlpserver
