
from modules.Configuration import *
from command import *
from statistics import *

command = eval(config.command+"()")
command.run()

# bht 
# remover pontos fora da curva do powerlaw
## valex como gold
#bnc nlpserver
