from modules.Configuration import config
from command import *

class CommandHandler:

  def __init__(self):
    pass

  def run(self, command):
    if command == 'extract-scf':
      operation = ExtractSCF.ExtractSCF()
    elif command == 'evaluate':
      operation = Evaluate.Evaluate()
    elif command == 'run-statistcs':
      operation = Evaluate.RunStatistcs()
    else:
      raise Exception("unknown command")

    operation.run()
