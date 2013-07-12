from modules.Configuration import config
from modules.FileUtils import FileUtils
from reader import CorpusIterator
import xml.etree.ElementTree as xml

## Reads corpus from xmls files
# @author Adriano Zanette
# @version 0.1
class XmlFileIterator(CorpusIterator):
  
  ## Class constructor
  # @author Adriano Zanette
  # @version 0.1
  # @return FileReader
  def __init__(self):
    self.files = []
    path = config.corpora.path  
    self.files = FileUtils.getFiles(path, extensions = ['.xml'])

  ## Read a line from a file
  # @author Adriano Zanette
  # @version 0.1
  # @return String
  def next(self):
    if len(self.files) == 0:
      raise StopIteration

    filename = self.files.pop()
    print 'Processing file %s...' % (filename)
    verbclass = xml.parse(filename).getroot()
    return verbclass
