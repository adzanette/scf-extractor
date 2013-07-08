
class CommandHandler():

  def __init__(self):
    pass

  def run(self, command):
    if command == 'extract-scf':
      operation = ExtractSCF()
    elif command == 'evaluate':
      operation = Evaluate()
    elif command == 'run-statistcs'
      operation = RunStatistcs()
    else:
      raise Exception("unknown command")

    opertion.run()
