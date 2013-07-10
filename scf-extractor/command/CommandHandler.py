from modules.Configuration import config
from command import *

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
      operation = ExtractSCF.ExtractSCF()
    elif command == 'evaluate':
      operation = Evaluate.Evaluate()
    elif command == 'run-statistics':
      operation = RunStatistics.RunStatistics()
    else:
      raise Exception("unknown command")

    operation.run()
