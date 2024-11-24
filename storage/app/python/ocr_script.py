import sys
import psutil
import time
import gc
import logging
from paddleocr import PaddleOCR
from PIL import Image

# Set up logging
logging.basicConfig(level=logging.INFO)

def get_available_memory():
    """Get the available memory on the server in MB."""
    memory = psutil.virtual_memory()
    return memory.available / (1024 ** 2)  # Return available memory in MB

def split_image(image_path, num_parts=4):
    """Split the image into smaller parts."""
    img = Image.open(image_path)
    width, height = img.size
    
    # Split image into parts (e.g., num_parts = 4 splits the image into 2x2 grid)
    part_width = width // 2
    part_height = height // 2
    parts = []
    
    # Create and save each part
    for i in range(2):
        for j in range(2):
            left = j * part_width
            upper = i * part_height
            right = left + part_width
            lower = upper + part_height
            
            part = img.crop((left, upper, right, lower))
            part_path = image_path.replace('.png', f'_part_{i}_{j}.png')
            part.save(part_path)
            parts.append(part_path)
    
    return parts

def extract_text(image_path):
    """Extract text from an image using PaddleOCR."""
    logging.info(f"Processing image: {image_path}")
    
    try:
        # Initialize PaddleOCR with minimal settings
        ocr = PaddleOCR(use_angle_cls=True, lang='en', rec_batch_num=1, table=False, formula=False, layout=False)

        # Perform OCR
        result = ocr.ocr(image_path, cls=True)
        
        # Extract text from OCR result
        extracted_text = ''
        for line in result[0]:
            word_info = line[1]
            word_text = word_info if isinstance(word_info, str) else str(word_info[0])
            extracted_text += word_text + '\n'
        
        # Clean up OCR model to free memory
        del ocr
        gc.collect()  # Force garbage collection
        
        return extracted_text

    except Exception as e:
        logging.error(f"Error processing {image_path}: {str(e)}")
        return ''

def save_text_to_file(image_path, extracted_text):
    """Save the extracted text to a file."""
    output_file = image_path.replace('.png', '.txt')  # Save as a text file
    try:
        with open(output_file, 'w') as f:
            f.write(extracted_text)
        logging.info(f"Extracted text saved to: {output_file}")
    except Exception as e:
        logging.error(f"Error saving text to file {output_file}: {str(e)}")

def process_image(image_path):
    """Process a single image by splitting, extracting text, and saving to file."""
    parts = split_image(image_path)  # Split the image into parts
    full_text = ''
    
    for part in parts:
        extracted_text = extract_text(part)
        full_text += extracted_text  # Concatenate text from each part
        
        # Clean up memory after processing each part
        del extracted_text
        gc.collect()
    
    # Save the extracted text to a file
    save_text_to_file(image_path, full_text)
    
    return full_text

if __name__ == '__main__':
    # Get a list of image paths from command line arguments
    image_paths = sys.argv[1:]

    for image_path in image_paths:
        # Process each image individually
        extracted_text = process_image(image_path)
        
        # Optionally print the extracted text for each image
        print(extracted_text)

        # Monitor available memory after each image
        available_memory = get_available_memory()
        logging.info(f"Available memory after processing {image_path}: {available_memory:.2f} MB")
        
        # Force garbage collection to free memory explicitly
        gc.collect()

