
__all__ = ['ExtractSCF']

from modules.Configuration import *
from reader import *
from extractor import *
from builder import *
from statistics import *
from filter import *

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
  # @version 0.1
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

    statistics = Statistics()
    statistics.run()

    scfFilter = Filter()
    scfFilter.filter()


