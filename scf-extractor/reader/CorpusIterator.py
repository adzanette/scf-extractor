
## Interface for a reader
# @author Adriano Zanette
# @version 0.1
class CorpusIterator:

  ## Class Constructor
  # @author Adriano Zanette
  # @version 0.1
  # @return BaseIterator
  def __init__(self):
    pass

  ## Class iterator
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def __iter__(self):
    return self

  ## It get the next sentence to parse
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def next(self):
    raise Exception("NotImplementedException")

