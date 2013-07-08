
from statistics import *

class RunStatistcs:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return RunStatistcs
  def __init__(self):
    pass

  ## Read, extract and store SCF's
  # @author Adriano Zanette
  # @version 0.10
  def run(self):
    
    statistics = Statistics()
    statistics.run()

    #scfFilter = Filter()
    #scfFilter.filter()