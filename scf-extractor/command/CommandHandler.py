from modules.Configuration import config
#from command import *

## This class run the command specified
# @author Adriano Zanette
# @version 0.1
class CommandHandler:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return CommandHandler
  def __init__(self):
    pass

  ## Execute the specified command
  # @author Adriano Zanette
  # @version 0.10
  # @param command String Command to be executed
  def run(self, command):
    if command == 'extract-scf':
      from command.ExtractSCF import ExtractSCF
      operation = ExtractSCF()
    elif command == 'evaluate':
      from command.Evaluate import Evaluate
      operation = Evaluate()
    elif command == 'run-statistics':
      from command.RunStatistics import RunStatistics
      operation = RunStatistics()
    else:
      raise Exception("unknown command")

    operation.run()
