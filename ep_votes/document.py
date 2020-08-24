import re
from enum import Enum
from dataclasses import dataclass

def parse_ref(ref):
    regex = r'^(A|B|RC)(?:-B)?(\d{1,2})-(\d{4})\/(\d{4})$'
    match = re.search(regex, ref)

    if not match:
        raise ValueError('Unrecognized document reference format')

    return Reference(
        type=Type[match.group(1)],
        term=int(match.group(2)),
        number=int(match.group(3)),
        year=int(match.group(4))
    )

class Type(Enum):
    A = 1  # report
    B = 2  # motion for resolution
    RC = 3 # joint motion for resolution

@dataclass
class Reference:
    type: Type
    term: int
    number: int
    year: int
