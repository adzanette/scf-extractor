from statistics import *

## This command run statistics modules over the frames
# @author Adriano Zanette
# @version 0.1
class RunStatistcs:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return RunStatistcs
  def __init__(self):
    pass

  ## Run statistics modules over the frames
  # @author Adriano Zanette
  # @version 0.10
  def run(self):
    
    statistics = Statistics()
    statistics.run()

    #scfFilter = Filter()
    #scfFilter.filter()