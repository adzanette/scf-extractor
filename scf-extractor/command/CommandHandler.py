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
  def run(self, cmd):
    if cmd == 'extract':
      cmd = 'ExtractSCF'
    elif cmd == 'rebuild':
      cmd = 'RebuildSCF'
    elif cmd == 'evaluate':
      cmd = 'Evaluate'
    elif cmd == 'statistics':
      cmd = 'RunStatistics'
    else:
      raise Exception("unknown command")

    exec "from command import %s as Command" % (cmd)
    command = Command()
    command.run()
