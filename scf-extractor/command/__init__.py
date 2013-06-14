
__all__ = ['ExtractSCF', 'Evaluate', 'RunStatistcs']

from modules.Configuration import *
from reader import *
from extractor import *
from builder import *
from statistics import *
from filter import *
from evaluator import Evaluator

## This class extracts SCF's from a raw source and stores the data processed in another
# @author Adriano Zanette
# @version 0.1
class ExtractSCF:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return ExtractSCF
  def __init__(self):
    pass

  ## Read, extract and store SCF's
  # @author Adriano Zanette
  # @version 0.10
  def run(self):
    moduleReader = config.reader.module
    corpus = eval(moduleReader+"Iterator.Iterator()")
    
    moduleExtractor = config.extractor.module
    extractor = eval(moduleExtractor+"Extractor.Extractor()")

    moduleBuilder = config.builder.module
    builder = eval(moduleBuilder+"Builder.Builder()")

    for sentence in corpus:  
      frames = extractor.extract(sentence)
      builder.buildFrames(sentence, frames)

class Evaluate:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Evaluate
  def __init__(self):
    pass

  ## Read, extract and store SCF's
  # @author Adriano Zanette
  # @version 0.1
  def run(self):
  
    evaluator = Evaluator()
    verbList = evaluator.verbList
    if len(verbList) == 0:
      evaluator.evaluate()
    elif len(verbList) == 1:
      evaluator.verbHistogram(verbList[0])
    else:
      evaluator.evaluateByVerbList(verbList)

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


