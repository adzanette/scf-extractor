
from modules.Configuration import *
from modules.FileUtils import FileUtils
from CorpusIterator import *
import xml.etree.ElementTree as xml

## Reads corpus from xmls files
# @author Adriano Zanette
# @version 0.1
class Iterator(CorpusIterator):
  
  ## Class constructor
  # @author Adriano Zanette
  # @version 0.1
  # @return FileReader
  def __init__(self):
    self.files = []
    path = config.reader.fileReader.path  
    self.files = FileUtils.getFiles(path, extensions = ['.xml'])

  ## Read a line from a file
  # @author Adriano Zanette
  # @version 0.1
  # @return String
  def next(self):
    if len(self.files) == 0:
      raise StopIteration

    filename = self.files.pop()
    verbclass = xml.parse(filename).getroot()
    return verbclass
