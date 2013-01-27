  
import os

## This class has static methods for file handling
# @author Adriano Zanette
# @version 1.0
class FileUtils(object):

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  @staticmethod
  def exists(filename):
    try:
      f = open(filename)
      f.close()
      return True
    except:
      return False

  ## test if is a dir
  # @author Adriano Zanette
  # @version 1.0
  # @param dirName String Directory name
  # @return Boolean 
  @staticmethod
  def isDir(dirName):
    dirName = dirName.replace('\\', '/')
    if os.path.isdir(dirName):
      splited = dirName.split('/')
      currentDir = splited[len(splited)-1]
      if not currentDir.startswith('.'):
        return True
      else:
        return False
    else:
      return False

  ## test if is a file
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String file name
  # @return Boolean 
  @staticmethod
  def isFile(filename):
    if os.path.isfile(filename) and not filename.startswith('.'):
      return True
    else:
      return False

  ## get all files from a dir
  # @author Adriano Zanette
  # @version 1.0
  # @param dirName String Directory name
  # @return List Returns an array with all files from the dir
  @staticmethod
  def getAllFiles(dirName, extensions = []):
    fileList = []
    for file in os.listdir(dirName):
      dirFile = os.path.join(dirName, file)
      if FileUtils.isFile(dirFile):
        extension = os.path.splitext(dirFile)[1]
        if len(extensions) == 0 or extension in extensions:
          fileList.append(dirFile)
      elif FileUtils.isDir(dirFile):
        fileList += FileUtils.getAllFiles(dirFile, extensions)
    return fileList