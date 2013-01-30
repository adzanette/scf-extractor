  

class XmlUtils(object):

  ## Find all elements using class namespace
  # @author Adriano Zanette
  # @version 0.1
  # @param doc XML XML node
  # @param xp Xpath query without namespace
  # @return All elements corresponding to xpath query
  @staticmethod
  def findall(namespace, doc, xp):
    num = xp.count('/')
    if num == 0:
      return doc.findall('{%s}%s' % (namespace, xp))  
    else:
      new_xp = xp.replace('/', '/{%s}')
      ns_tup = (namespace,) * num
      return doc.findall(new_xp % ns_tup) 

