from setuptools import setup

setup(
    name='SCFextractor',
    version='0.1',
    description='A Python library for extracting subcategorization frames',
    author='Adriano Zanette',
    author_email='adzanette@gmail.com',
    url='http://flavours.me/adzanette',
    packages=['SCFExtractor'],
    classifiers=[
        "License :: OSI Approved :: MIT License",
        "Programming Language :: Python",
        "Development Status :: 4 - Beta",
        "Intended Audience :: Developers",
        "Topic :: Natural Language Processing :: Subcategorization Frames",
    ],
    keywords='natural language processing nlp subcategorization frames scf',
    license='MIT',
    install_requires=[
        'setuptools',
    ],
)
