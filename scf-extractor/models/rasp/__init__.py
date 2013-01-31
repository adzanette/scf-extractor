
__all__ = [
             'Token'
            ,'Sentence'
          ]

import operator
import re

## Exception for relations undefined
# @author Adriano Zanette
# @version 0.1
class RelationException(Exception):
    pass

## Model for tokens
# @author Adriano Zanette
# @version 0.1
class Token(object):
  
  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Token
  def __init__(self):
    self.id = -1
    self.father = None
    self.word = ''
    self.morpho = ''
    self.modifier = ''
    self.relation = ''
    self.children = []
    self.relationType = ''
    self.isPassive = False
  
  ## Verifies if token is a verb
  # @author Adriano Zanette
  # @version 0.1
  # @return Boolean
  def isVerb(self):
    if self.morpho.startswith('V') and self.relation <> 'aux':
      return True
    return False

  ## Get all the token phrasal core 
  # @author Adriano Zanette
  # @version 0.1
  # @return String, Integer, Integer
  def getPhrasalCore(self):
    allChildren = self.getChildren()
    allChildren.sort(key = operator.attrgetter('id'))
    phrase = ''
    begin = self.id
    end = self.id
    for child in allChildren:
      if child.id < begin: 
        begin = child.id
      if child.id > end:
        end = child.id 
      if child.modifier:
        phrase += child.word + '+' + child.modifier + ' '
      else:
        phrase += child.word + ' '
    phrase = phrase[:-1]
    return phrase, begin, end  
  
  ## Get all the token descendants
  # @author Adriano Zanette
  # @version 0.1
  # @return List
  def getChildren(self, token = None, visited = []):
    if not token:
      token = self
    directChildren = token.children
    visited += [token.id]
    children = []
    for child in directChildren:
      if child.id not in visited:
        children += self.getChildren(child, visited)
    return sorted(set(directChildren + children + [token])) 


## Model for sentences
# @author Adriano Zanette
# @version 0.1    
class Sentence(object):

  ignoreTokens = [';', 'ellip', 'to', 'lambda']
  tokenRE = re.compile(r'^(?P<function>[a-z]+)$|^(?P<word>[^\+]+)\+?((?P<modifier>.+))?:(?P<position>\d+)_(?P<morpho>.+)$', re.L)

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def __init__(self, sentence):
    self.id = sentence.id
    self.raw = sentence.raw
    self.parsed = sentence.parsed
    self.tokens = {}

  ## Adds tokens to the sentence based on a relationship
  # @author Adriano Zanette
  # @version 0.1
  # @param args List list of arguments representing a line
  # @return None
  def addRelationship(self, args):
    # arguments not documented: obj, passive, arg_mod, arg, pmod, comp
    relations = {
      'binary': ['conj', 'aux', 'det','obj', 'dobj', 'obj2', 'iobj', 'pcomp', 'pmod', 'comp'],
      'ternaryFirst': ['ncmod', 'xmod', 'cmod', 'xcomp', 'ccomp', 'ta', 'arg_mod', 'arg'],
      'ternaryLast': ['ncsubj', 'xsubj', 'csubj']
    }

    relation = args.pop(0)

    if relation in ['EMPTY']:
      return None

    if relation == 'passive':
      token = self.getToken(args[0])
      token.isPassive = True
      self.tokens[token.id] = token
      return None      

    if relation in relations['binary']:
      relationType = ''
      father = args[0]
      child = args[1]
    elif relation in relations['ternaryFirst']:
      relationType = args[0]
      father = args[1]
      child = args[2]
    elif relation in relations['ternaryLast']:
      relationType = args[2]
      father = args[0]
      child = args[1]
    else:
      print args
      raise RelationException('Undefined relation '+relation)
  
    if father in self.ignoreTokens or child in self.ignoreTokens:
      return None 

    fatherToken = self.getToken(father)
    childToken = self.getToken(child)

    fatherToken.children.append(childToken)
    childToken.father = fatherToken
    childToken.relation = relation
    childToken.relationType = relationType

    self.tokens[fatherToken.id] = fatherToken
    self.tokens[childToken.id] = childToken

  ## Process a token line
  # @author Adriano Zanette
  # @version 0.1
  # @param strToken String Token line
  # @return position, word, modifier, morpho
  def processToken(self, strToken):
   
    token = re.search(self.tokenRE, strToken)
    if token:
      if token.group('function'):
        word =  token.group('function')
      else:
        word = token.group('word')
        modifier = token.group('modifier')
        position = token.group('position')
        morpho = token.group('morpho')
    else:
      raise Exception(strToken)

    return position, word, modifier, morpho

  ## Process a token line and returns a Token
  # @author Adriano Zanette
  # @version 0.1
  # @param strToken String Token line
  # @return Token
  def getToken(self, strToken):

    position, word, modifier, morpho = self.processToken(strToken)

    if position in self.tokens:
      token = self.tokens[position]
    else:
      token = Token()
      token.id = position
      token.word = word.lower()
      token.morpho = morpho
      token.modifier = modifier
    
    return token

  ## Gets all verbs from a sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return List
  def getVerbs(self):
    verbs = []
    for key, token in self.tokens.iteritems():
      if token.isVerb():
        verbs.append(token)
    return verbs
