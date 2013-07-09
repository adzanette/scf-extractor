
from modules.Configuration import config
from modules.FileUtils import FileUtils
from CorpusIterator import *

## Reads corpus from a file or all file of a folder
# @author Adriano Zanette
# @version 0.1
class FileCorpusIterator(CorpusIterator):
  
  ## Class constructor
  # @author Adriano Zanette
  # @version 0.1
  # @return FileReader
  def __init__(self):
    self.filePointer = None
    self.EOF = False
    self.files = []
    path = config.corpora.path  
    self.files = FileUtils.getFiles(path)
    self.openNextFile()

  ## Opens the next file
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def openNextFile(self):
    if self.filePointer:
      self.filePointer.close()

    if len(self.files) == 0:
      self.EOF = True
      return False

    filename = self.files.pop()
    print filename
    self.filePointer = stream = open(filename, 'r')
    return True
    
  ## Read a line from a file
  # @author Adriano Zanette
  # @version 0.1
  # @return String
  def next(self):
    line = self.filePointer.readline()
    if not line:    
      if self.openNextFile():
        return None
      else:
        raise StopIteration
    else:
      return line