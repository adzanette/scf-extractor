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
    exec "from reader import %sIterator as Iterator" % (config.reader.module)
    corpus = Iterator()
    
    exec "from extractor import %sExtractor as Extractor" % (config.extractor.module)
    extractor = Extractor()

    exec "from builder import %sBuilder as Builder" % (config.builder.module)
    builder = Builder()

    for sentence in corpus:  
      frames = extractor.extract(sentence)
      builder.buildFrames(sentence, frames)
