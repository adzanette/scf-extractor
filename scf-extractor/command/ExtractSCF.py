from reader import *
from extractor import *
from builder import *
from modules.Configuration import config

## This command extracts SCF's from a raw source and stores the data processed in another
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
    moduleBuilder = config.builder.module
    builder = eval(moduleBuilder+"Builder.Builder()")

    moduleReader = config.reader.module
    corpus = eval(moduleReader+"Iterator.Iterator()")
    
    moduleExtractor = config.extractor.module
    extractor = eval(moduleExtractor+"Extractor.Extractor()")

   

    for sentence in corpus:  
      frames = extractor.extract(sentence)
      builder.buildFrames(sentence, frames)
